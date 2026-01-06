window.csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

class ModalManager {
    constructor() {
        this.init();
    }

    init() {
        document.body.addEventListener("click", (e) => {
            if (e.target.closest("#add-customer-btn")) {
                e.preventDefault();
                this.openModal("customer-modal-add");
            }

            const editBtn = e.target.closest(".edit-customer-btn");
            if (editBtn) {
                e.preventDefault();
                const customerId = editBtn.dataset.customerId;
                if (customerId) {
                    this.openModal(`customer-modal-edit-${customerId}`);
                }
            }

            const closeBtn = e.target.closest(".close-modal-btn");
            const cancelBtn = e.target.closest(".cancel-modal-btn");
            const modalOverlay = e.target.closest(".modal-overlay");

            if (closeBtn || cancelBtn) {
                e.preventDefault();
                const modalId = closeBtn
                    ? closeBtn.dataset.modalId
                    : cancelBtn.dataset.modalId;
                if (modalId) {
                    this.closeModal(modalId);
                }
            } else if (modalOverlay && e.target === modalOverlay) {
                this.closeModal(modalOverlay.id);
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                const openModal = document.querySelector(
                    ".modal-overlay.modal-show"
                );
                if (openModal) this.closeModal(openModal.id);
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        document.body.style.overflow = "hidden";
        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.add("modal-show");
            feather.replace();
        }, 10);
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.remove("modal-show");
        setTimeout(() => {
            modal.classList.add("hidden");
            document.body.style.overflow = "";
            const form = modal.querySelector("form");
            if (form) form.reset();
        }, 300);
    }
}

class SearchManager {
    constructor(modalManager) {
        this.modalManager = modalManager;
        this.searchInput = document.getElementById("search-input");
        this.clearSearchBtn = document.getElementById("clear-search");
        this.customersGrid = document.getElementById("customers-grid");
        this.searchResultsContainer = document.getElementById("search-results");
        this.loadingState = document.getElementById("loading-state");
        this.paginationLinks = document.getElementById("pagination-links");
        this.abortController = null;
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        let debounceTimer;
        this.searchInput.addEventListener("input", (e) => {
            const term = e.target.value.trim();
            this.toggleClearButton(term);
            clearTimeout(debounceTimer);

            if (this.abortController) {
                this.abortController.abort();
            }

            if (term.length === 0) {
                this.resetSearch();
                return;
            }

            if (term.length < 2) {
                this.showMessage(
                    "Lanjutkan...",
                    "Masukan minimalnya 2 huruf untuk melanjutkan pencarian."
                );
                return;
            }

            debounceTimer = setTimeout(() => this.performSearch(term), 400);
        });

        this.clearSearchBtn.addEventListener("click", () => {
            this.searchInput.value = "";
            this.toggleClearButton("");
            this.resetSearch();
        });
    }

    toggleClearButton(term) {
        this.clearSearchBtn.classList.toggle("hidden", term.length === 0);
    }

    async performSearch(term) {
        this.showLoading();
        this.abortController = new AbortController();

        try {
            const response = await fetch(
                `/admin/customers/search?q=${encodeURIComponent(term)}`,
                {
                    signal: this.abortController.signal,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                }
            );

            if (!response.ok)
                throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            let customers = [];

            if (Array.isArray(data)) {
                customers = data;
            } else if (data && Array.isArray(data.customers)) {
                customers = data.customers;
            } else if (data && data.data && Array.isArray(data.data)) {
                customers = data.data;
            }

            const modals = data.modals || "";
            this.displayResults(customers, term, modals);
        } catch (error) {
            if (error.name !== "AbortError") {
                console.error("Search error:", error);
                this.showMessage(
                    "Terjadi Kesalahan",
                    "Gagal memuat hasil pencarian. Coba lagi.",
                    "alert-circle",
                    "red"
                );
            }
        } finally {
            this.hideLoading();
        }
    }

    displayResults(customers, term, modalsHtml) {
        this.hideOriginalGrid();

        if (customers.length === 0) {
            this.showMessage(
                "Hasil Tidak Ditemukan",
                `Tidak ada pelanggan untuk kata kunci "<span class="font-semibold">${this.escapeHtml(
                    term
                )}</span>".`
            );
            return;
        }

        const resultsHTML = customers
            .map((customer) => this.buildCustomerCard(customer))
            .join("");
        this.searchResultsContainer.innerHTML = `
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Menemukan <span class="font-semibold">${
                            customers.length
                        }</span> hasil untuk
                        <span class="font-semibold">"${this.escapeHtml(
                            term
                        )}"</span>
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    ${resultsHTML}
                </div>
            `;

        if (modalsHtml && typeof modalsHtml === "string") {
            document.body.insertAdjacentHTML("beforeend", modalsHtml);
        }

        feather.replace();
        this.animateResults();
    }

    buildCustomerCard(customer) {
        const initials = this.getInitials(customer.name);
        const joinDate = new Date(customer.created_at).toLocaleDateString(
            "id-ID",
            {
                day: "2-digit",
                month: "short",
                year: "numeric",
            }
        );

        let phone = customer.phone;
        if (phone && phone.startsWith("0")) {
            phone = "62" + phone.substring(1);
        }
        const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, "")}`;

        return `
                <div class="customer-card border border-gray-200 rounded-2xl p-6 fade-in-up" style="opacity:0; transform:translateY(20px);">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="avatar w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                                ${initials}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">${this.escapeHtml(
                                    customer.name
                                )}</h3>
                                <p class="text-sm text-gray-500">${this.escapeHtml(
                                    customer.email
                                )}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2 mb-5">
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="phone" class="w-4 h-4 mr-2 text-gray-400"></i><span>${this.escapeHtml(
                            customer.phone
                        )}</span></div>
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="map-pin" class="w-4 h-4 mr-2 text-gray-400"></i><span class="line-clamp-1">${this.escapeHtml(
                            customer.address
                        )}</span></div>
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="calendar" class="w-4 h-4 mr-2 text-gray-400"></i><span>Bergabung: ${joinDate}</span></div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 mb-5">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
                            <p class="text-lg font-bold text-gray-900">${
                                customer.transactions_count || 0
                            }</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                            <p class="text-lg font-bold text-indigo-600">${this.formatRupiahSingkat(
                                customer.transactions_sum_total || 0
                            )}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button data-customer-id="${
                            customer.customer_id
                        }" class="edit-customer-btn action-btn flex-1 px-4 py-2.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600"><i data-feather="edit-2" class="w-4 h-4"></i><span class="font-medium">Edit</span></button>
                        <form method="POST" action="/admin/customers/${
                            customer.customer_id
                        }" class="delete-form flex-1">
                            <input type="hidden" name="_token" value="${
                                window.csrfToken
                            }">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="delete-btn action-btn w-full px-4 py-2.5 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600"><i data-feather="trash-2" class="w-4 h-4"></i><span class="font-medium">Hapus</span></button>
                        </form>
                    </div>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-3 text-center font-medium">Hubungi Pelanggan</p>
                        <div class="flex gap-2">
                            <a href="mailto:${this.escapeHtml(
                                customer.email
                            )}" target="_blank" class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700"><i data-feather="mail" class="w-4 h-4"></i><span class="font-medium">Email</span></a>
                            <a href="${whatsappUrl}" target="_blank" class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-green-50 hover:bg-green-100 flex items-center justify-center text-green-700"><i data-feather="message-circle" class="w-4 h-4"></i><span class="font-medium">WhatsApp</span></a>
                        </div>
                    </div>
                </div>
                `;
    }

    showLoading() {
        this.hideOriginalGrid();
        this.loadingState.classList.remove("hidden");
        this.searchResultsContainer.innerHTML = "";
    }

    hideLoading() {
        this.loadingState.classList.add("hidden");
    }

    showMessage(title, text, icon = "search", color = "gray") {
        this.hideOriginalGrid();
        this.searchResultsContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="p-4 bg-${color}-100 rounded-full mb-4">
                        <i data-feather="${icon}" class="w-8 h-8 text-${color}-500"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">${title}</h2>
                    <p class="text-gray-500 text-sm">${text}</p>
                </div>
            `;
        feather.replace();
    }

    resetSearch() {
        this.customersGrid.classList.remove("hidden");
        this.paginationLinks?.classList.remove("hidden");
        this.searchResultsContainer.classList.add("hidden");
        this.searchResultsContainer.innerHTML = "";
        document
            .querySelectorAll(".search-modal")
            .forEach((modal) => modal.remove());
    }

    hideOriginalGrid() {
        this.customersGrid.classList.add("hidden");
        this.paginationLinks?.classList.add("hidden");
        this.searchResultsContainer.classList.remove("hidden");
    }

    animateResults() {
        this.searchResultsContainer
            .querySelectorAll(".customer-card")
            .forEach((card, index) => {
                setTimeout(() => {
                    card.style.transition =
                        "opacity 0.4s ease, transform 0.4s ease";
                    card.style.opacity = "1";
                    card.style.transform = "translateY(0)";
                }, index * 50);
            });
    }

    getInitials(name) {
        const parts = name.split(" ").filter(Boolean);
        if (parts.length > 1)
            return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
        return name.substring(0, 2).toUpperCase();
    }

    formatRupiahSingkat(number) {
        if (!number || number === 0) return "Rp0";
        const n = Math.floor(number);
        if (n >= 1000000000) {
            const val = n / 1000000000;
            return "Rp" + (val % 1 === 0 ? val : val.toFixed(1)) + "M";
        }
        if (n >= 1000000) {
            const val = n / 1000000;
            return "Rp" + (val % 1 === 0 ? val : val.toFixed(1)) + "Jt";
        }
        if (n >= 1000) {
            const val = n / 1000;
            return "Rp" + (val % 1 === 0 ? val : val.toFixed(1)) + "K";
        }
        return "Rp" + n;
    }

    escapeHtml(str) {
        if (!str) return "";
        return str.replace(
            /[&<>"']/g,
            (match) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                }[match])
        );
    }
}

class DeleteManager {
    constructor() {
        document.body.addEventListener("click", (e) => {
            const deleteBtn = e.target.closest(".delete-btn");
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest(".delete-form");
                const card = deleteBtn.closest(".customer-card");
                const customerName =
                    card?.querySelector("h3")?.textContent || "pelanggan ini";
                this.confirmDelete(form, customerName);
            }
        });
    }

    confirmDelete(form, customerName) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Apakah Anda yakin?",
                html: `Anda akan menghapus <strong>${this.escapeHtml(
                    customerName
                )}</strong>. Tindakan ini tidak dapat dibatalkan.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menghapus ${customerName}?`)) {
                form.submit();
            }
        }
    }

    escapeHtml(str) {
        const p = document.createElement("p");
        p.textContent = str;
        return p.innerHTML;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const modalManager = new ModalManager();
    new SearchManager(modalManager);
    new DeleteManager();
    feather.replace();
    document
        .getElementById("importFile")
        ?.addEventListener("change", function () {
            if (this.files.length > 0) {
                document.getElementById("importForm").submit();
            }
        });
});
