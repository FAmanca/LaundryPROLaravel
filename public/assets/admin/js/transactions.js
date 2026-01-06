// Initialize Feather Icons
feather.replace();

// ========== MODAL HELPER FUNCTIONS ==========
function openModal(modalElement) {
    modalElement.classList.remove("hidden");
    setTimeout(() => {
        modalElement.classList.add("modal-show");
        feather.replace();
    }, 10);
    document.body.style.overflow = "hidden";
}

function closeModal(modalElement) {
    modalElement.classList.remove("modal-show");
    setTimeout(() => {
        modalElement.classList.add("hidden");
        document.body.style.overflow = "";
    }, 300);
}

// ========== VIEW DETAIL MODAL ==========
document.querySelectorAll(".view-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.viewId;
        const modal = document.getElementById(`detail-modal-${id}`);
        if (modal) openModal(modal);
    });
});

// ========== EDIT MODAL ==========
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.editId;
        const modal = document.getElementById(`edit-modal-${id}`);
        if (modal) openModal(modal);
    });
});

// Close modals
document.querySelectorAll(".close-modal").forEach((btn) => {
    btn.addEventListener("click", () => {
        const modal = btn.closest(".modal-overlay");
        if (modal) closeModal(modal);
    });
});

// Close on backdrop click
document.querySelectorAll(".modal-overlay").forEach((modal) => {
    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeModal(modal);
    });
});

// ========== PAYMENT STATUS CHANGE ==========
document.querySelectorAll('select[name="payment_status"]').forEach((select) => {
    select.addEventListener("change", function () {
        const modal = this.closest(".modal-overlay");
        const transactionId = modal.id.replace("edit-modal-", "");
        const amountField = document.getElementById(
            `amount-paid-field-${transactionId}`
        );

        if (this.value === "Partial") {
            amountField?.classList.remove("hidden");
        } else {
            amountField?.classList.add("hidden");
        }
    });
});

// ========== CHECKBOX SELECTION ==========
const selectAllCheckbox = document.getElementById("select-all");
const rowCheckboxes = document.querySelectorAll(".row-checkbox");
const bulkActionsBar = document.getElementById("bulk-actions-bar");
const selectedCountSpan = document.getElementById("selected-count");
const bulkClearBtn = document.getElementById("bulk-clear-btn");

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
    const count = checkedBoxes.length;

    if (count > 0) {
        bulkActionsBar.classList.add("show");
        selectedCountSpan.textContent = count;
    } else {
        bulkActionsBar.classList.remove("show");
    }
}

selectAllCheckbox?.addEventListener("change", function () {
    rowCheckboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

rowCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
        updateBulkActions();

        const allChecked = Array.from(rowCheckboxes).every((cb) => cb.checked);
        const someChecked = Array.from(rowCheckboxes).some((cb) => cb.checked);

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
    });
});

bulkClearBtn?.addEventListener("click", () => {
    rowCheckboxes.forEach((checkbox) => (checkbox.checked = false));
    if (selectAllCheckbox) selectAllCheckbox.checked = false;
    updateBulkActions();
});

// ========== EMAIL REMINDER ==========
document.querySelectorAll(".email-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const customerName = btn.dataset.customerName;
        const customerEmail = btn.dataset.customerEmail;
        const transactionId = btn.dataset.emailId;
        const url = btn.dataset.url;
        const transactionCode = btn.dataset.transactionCode;

        Swal.fire({
            title: "Kirim Email Reminder?",
            html: `
                        <div class="text-sm text-gray-700 leading-relaxed">
                            Email akan dikirim ke:
                            <div class="mt-3 p-3 rounded-lg bg-purple-50 text-left">
                                <p class="font-semibold text-gray-800">${customerEmail}</p>
                                <p class="text-gray-500 text-xs mt-1">Kode Transaksi: ${transactionCode}</p>
                            </div>
                        </div>
                    `,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#8b5cf6",
            cancelButtonColor: "#6b7280",
            confirmButtonText:
                '<i data-feather="mail" class="w-4 h-4 inline mr-1"></i> Kirim Email',
            cancelButtonText: "Batal",
            customClass: {
                popup: "rounded-2xl",
                confirmButton: "rounded-xl px-6",
                cancelButton: "rounded-xl px-6",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                // Swal.fire({
                //     title: 'Mengirim email...',
                //     allowOutsideClick: false,
                //     showConfirmButton: false,
                //     didOpen: () => Swal.showLoading()
                // });

                fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        email: customerEmail,
                        name: customerName,
                    }),
                })
                    // .then(res => res.json())
                    .then((data) => {
                        Swal.fire({
                            icon: "success",
                            title: "Email Terkirim!",
                            text: `Reminder berhasil dikirim ke ${customerName}`,
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: "rounded-2xl",
                            },
                        });
                    })
                    .catch((err) => {
                        console.error(err);
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Mengirim Email!",
                            text: "Terjadi kesalahan saat mengirim email.",
                            customClass: {
                                popup: "rounded-2xl",
                            },
                        });
                    });
            }
        });
    });
});

// ========== WHATSAPP REMINDER ==========
document.querySelectorAll(".wa-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const customerName = btn.dataset.customerName;
        const customerPhone = btn.dataset.customerPhone;

        Swal.fire({
            title: "Kirim WhatsApp Reminder?",
            html: `Pesan akan dikirim ke:<br><strong>${customerName}</strong><br>${customerPhone}`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#10b981",
            cancelButtonColor: "#6b7280",
            confirmButtonText:
                '<i data-feather="message-circle" class="w-4 h-4 inline mr-1"></i> Kirim WhatsApp',
            cancelButtonText: "Batal",
            customClass: {
                popup: "rounded-2xl",
                confirmButton: "rounded-xl px-6",
                cancelButton: "rounded-xl px-6",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Mengirim pesan...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading(),
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: "success",
                        title: "Pesan Terkirim!",
                        text: `Reminder berhasil dikirim ke ${customerName}`,
                        timer: 3000,
                        showConfirmButton: false,
                        customClass: {
                            popup: "rounded-2xl",
                        },
                    });
                }, 1500);
            }
        });
    });
});

// ========== BULK EMAIL ==========
document.getElementById("bulk-email-btn")?.addEventListener("click", () => {
    const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
    const count = checkedBoxes.length;

    if (count === 0) {
        Swal.fire("Pilih minimal 1 pelanggan!", "", "warning");
        return;
    }

    const transactionIds = Array.from(checkedBoxes).map((cb) => cb.dataset.id);
    const url = document.getElementById("bulk-email-btn").dataset.url;

    Swal.fire({
        title: "Kirim Email Massal?",
        text: `Email reminder akan dikirim ke ${count} pelanggan`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#8b5cf6",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Kirim Email",
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl",
            confirmButton: "rounded-xl px-6",
            cancelButton: "rounded-xl px-6",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Mengirim email...",
                text: `Mengirim ke ${count} penerima`,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });

            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    transaction_ids: transactionIds,
                }),
            })
                // .then(res => res.json())
                .then((data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Semua Email Terkirim!",
                        text: `${count} email reminder berhasil dikirim`,
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: "rounded-2xl",
                        },
                    });

                    // Clear selection
                    bulkClearBtn.click();
                })
                .catch((err) => {
                    console.error(err);
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Mengirim Email!",
                        text: "Terjadi kesalahan saat mengirim email.",
                        customClass: {
                            popup: "rounded-2xl",
                        },
                    });
                });
        }
    });
});

// ========== BULK WHATSAPP ==========
document.getElementById("bulk-whatsapp-btn")?.addEventListener("click", () => {
    const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
    const count = checkedBoxes.length;

    Swal.fire({
        title: "Kirim WhatsApp Massal?",
        text: `Pesan reminder akan dikirim ke ${count} pelanggan`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#10b981",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Kirim WhatsApp",
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl",
            confirmButton: "rounded-xl px-6",
            cancelButton: "rounded-xl px-6",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Mengirim pesan...",
                text: `Mengirim ke ${count} penerima`,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });

            setTimeout(() => {
                Swal.fire({
                    icon: "success",
                    title: "Semua Pesan Terkirim!",
                    text: `${count} pesan WhatsApp berhasil dikirim`,
                    timer: 3000,
                    showConfirmButton: false,
                    customClass: {
                        popup: "rounded-2xl",
                    },
                });

                // Clear selection
                bulkClearBtn.click();
            }, 2000);
        }
    });
});

// ========== DROPDOWN FILTERS ==========
const filterBayarBtn = document.getElementById("filter-bayar-btn");
const dropdownBayar = document.getElementById("dropdown-bayar");
const filterLaundryBtn = document.getElementById("filter-laundry-btn");
const dropdownLaundry = document.getElementById("dropdown-laundry");

filterBayarBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownBayar.classList.toggle("hidden");
    dropdownLaundry.classList.add("hidden");
    feather.replace();
});

filterLaundryBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownLaundry.classList.toggle("hidden");
    dropdownBayar.classList.add("hidden");
    feather.replace();
});

// Close dropdowns when clicking outside
document.addEventListener("click", () => {
    dropdownBayar?.classList.add("hidden");
    dropdownLaundry?.classList.add("hidden");
});

// Handle dropdown item clicks
dropdownBayar?.querySelectorAll("button").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        console.log("Filter Status Bayar:", btn.textContent.trim());
        dropdownBayar.classList.add("hidden");
        // Add your filter logic here
    });
});

dropdownLaundry?.querySelectorAll("button").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        console.log("Filter Status Laundry:", btn.textContent.trim());
        dropdownLaundry.classList.add("hidden");
        // Add your filter logic here
    });
});

// ========== SEARCH FUNCTIONALITY ==========
const searchInput = document.getElementById("search-transaction");
let searchTimeout = null;

searchInput?.addEventListener("input", (e) => {
    const searchTerm = e.target.value.trim();

    clearTimeout(searchTimeout);

    if (searchTerm.length < 2) {
        document.getElementById("transactions-table-body").innerHTML = "";
        return;
    }

    searchTimeout = setTimeout(async () => {
        try {
            console.log("Searching for:", searchTerm);
            const params = new URLSearchParams({
                q: searchTerm,
            });
            const response = await fetch(
                `/admin/transactions/search?${params}`,
                {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                }
            );

            if (!response.ok) throw new Error("Gagal memuat hasil pencarian");

            const html = await response.text();
            document.getElementById("transactions-table-body").innerHTML = html;
            feather.replace();
        } catch (error) {
            console.error(error);
        }
    }, 400);
});

// ========== DELETE CONFIRMATION ==========
document.querySelectorAll(".delete-btn").forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const form = deleteBtn.closest(".delete-form");

        Swal.fire({
            title: "Hapus Transaksi?",
            text: "Data transaksi akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal",
            reverseButtons: true,
            customClass: {
                popup: "rounded-2xl",
                confirmButton: "rounded-xl px-6",
                cancelButton: "rounded-xl px-6",
            },
            showClass: {
                popup: "animate__animated animate__fadeInDown animate__faster",
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp animate__faster",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Menghapus transaksi...",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                form.submit();
            }
        });
    });
});

// ========== FORM SUBMIT LOADING ==========
document.querySelectorAll(".save-edit-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const form = e.target.closest("form");
        if (form.checkValidity()) {
            Swal.fire({
                title: "Memperbarui transaksi...",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }
    });
});

// ========== KEYBOARD SHORTCUTS ==========
document.addEventListener("keydown", (e) => {
    // ESC key to close modals
    if (e.key === "Escape") {
        document
            .querySelectorAll(".modal-overlay.modal-show")
            .forEach((modal) => {
                closeModal(modal);
            });
        dropdownBayar?.classList.add("hidden");
        dropdownLaundry?.classList.add("hidden");
    }

    // CTRL/CMD + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        searchInput?.focus();
    }
});

// ========== AJAX SEARCH & FILTER SYSTEM ==========
class TransactionFilter {
    constructor() {
        this.searchInput = document.getElementById("search-transaction");
        this.filterBayar = document.getElementById("dropdown-bayar");
        this.filterLaundry = document.getElementById("dropdown-laundry");
        this.tbody = document.querySelector("tbody");
        this.paginationContainer = document.querySelector(".pagination");
        this.searchTimeout = null;
        this.currentFilters = {
            search: "",
            payment_status: "",
            laundry_status: "",
        };
        this.checkedIds = new Set();
        this.init();
    }

    init() {
        this.setupSearch();
        this.setupFilters();
        this.setupPagination();
        this.restoreCheckboxes();
    }

    // Save checkbox state
    saveCheckboxState() {
        this.checkedIds.clear();
        document.querySelectorAll(".row-checkbox:checked").forEach((cb) => {
            this.checkedIds.add(cb.dataset.id);
        });
    }

    // Restore checkbox state
    restoreCheckboxes() {
        this.checkedIds.forEach((id) => {
            const checkbox = document.querySelector(
                `.row-checkbox[data-id="${id}"]`
            );
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        updateBulkActions();
    }

    setupSearch() {
        if (!this.searchInput) return;

        this.searchInput.addEventListener("input", (e) => {
            clearTimeout(this.searchTimeout);
            this.currentFilters.search = e.target.value.trim();

            this.searchTimeout = setTimeout(() => {
                this.loadData();
            }, 400);
        });
    }

    setupFilters() {
        // Payment Status Filter
        this.filterBayar?.querySelectorAll("button").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                const text = btn.textContent.trim().toLowerCase();

                if (text.includes("semua")) {
                    this.currentFilters.payment_status = "";
                } else if (text.includes("lunas")) {
                    this.currentFilters.payment_status = "Paid";
                } else if (text.includes("dp")) {
                    this.currentFilters.payment_status = "Partial";
                } else if (text.includes("belum")) {
                    this.currentFilters.payment_status = "Unpaid";
                }

                this.loadData();
                this.filterBayar.classList.add("hidden");
            });
        });

        // Laundry Status Filter
        this.filterLaundry?.querySelectorAll("button").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                const text = btn.textContent.trim().toLowerCase();

                if (text.includes("semua")) {
                    this.currentFilters.laundry_status = "";
                } else if (text.includes("masuk")) {
                    this.currentFilters.laundry_status = "Pending";
                } else if (text.includes("proses")) {
                    this.currentFilters.laundry_status = "Process";
                } else if (text.includes("siap")) {
                    this.currentFilters.laundry_status = "Completed";
                } else if (text.includes("selesai")) {
                    this.currentFilters.laundry_status = "Picked Up";
                }

                this.loadData();
                this.filterLaundry.classList.add("hidden");
            });
        });
    }

    setupPagination() {
        // Intercept pagination clicks
        document.addEventListener("click", (e) => {
            const paginationLink = e.target.closest(".pagination a");
            if (
                paginationLink &&
                !paginationLink.classList.contains("disabled")
            ) {
                e.preventDefault();
                const url = new URL(paginationLink.href);
                const page = url.searchParams.get("page");
                if (page) {
                    this.loadData(page);
                }
            }
        });
    }

    async loadData(page = 1) {
        this.saveCheckboxState();

        // Show loading
        this.showLoading();

        try {
            const params = new URLSearchParams({
                page: page,
                ...this.currentFilters,
            });

            // Remove empty params
            for (let [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }

            const response = await fetch(`/admin/transactions?${params}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "text/html",
                },
            });

            if (!response.ok) throw new Error("Failed to load data");

            const html = await response.text();
            this.tbody.innerHTML = html;

            // Update pagination
            this.updatePagination(response.url);

            // Restore checkboxes
            this.restoreCheckboxes();

            // Reinitialize icons and event listeners
            feather.replace();
            this.reinitializeEventListeners();
        } catch (error) {
            console.error("Error loading data:", error);
            this.showError();
        }
    }

    updatePagination(url) {
        // You can fetch and update pagination separately if needed
        // For now, we'll keep the existing pagination
    }

    showLoading() {
        this.tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                        <p class="text-sm text-gray-500">Memuat data...</p>
                    </div>
                </td>
            </tr>
        `;
    }

    showError() {
        this.tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-feather="alert-circle" class="w-12 h-12 text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Terjadi Kesalahan</h3>
                        <p class="text-sm text-gray-500">Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                </td>
            </tr>
        `;
        feather.replace();
    }

    reinitializeEventListeners() {
        // Reinitialize all button event listeners
        // View buttons
        document.querySelectorAll(".view-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                const id = btn.dataset.viewId;
                const modal = document.getElementById(`detail-modal-${id}`);
                if (modal) openModal(modal);
            });
        });

        // Edit buttons
        document.querySelectorAll(".edit-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                const id = btn.dataset.editId;
                const modal = document.getElementById(`edit-modal-${id}`);
                if (modal) openModal(modal);
            });
        });

        // Email buttons
        document.querySelectorAll(".email-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                // Your email logic here
            });
        });

        // WhatsApp buttons
        document.querySelectorAll(".wa-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                // Your WhatsApp logic here
            });
        });

        // Delete buttons
        document.querySelectorAll(".delete-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                // Your delete logic here
            });
        });

        // Checkboxes
        document.querySelectorAll(".row-checkbox").forEach((checkbox) => {
            checkbox.addEventListener("change", () => {
                updateBulkActions();

                const allChecked = Array.from(
                    document.querySelectorAll(".row-checkbox")
                ).every((cb) => cb.checked);
                const someChecked = Array.from(
                    document.querySelectorAll(".row-checkbox")
                ).some((cb) => cb.checked);

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate =
                        someChecked && !allChecked;
                }
            });
        });
    }
}

// Initialize the filter system
const transactionFilter = new TransactionFilter();

// ========== AUTO-REFRESH FEATHER ICONS ==========
const observer = new MutationObserver(() => {
    feather.replace();
});

observer.observe(document.body, {
    childList: true,
    subtree: true,
});

// Initial icon replacement
feather.replace();
