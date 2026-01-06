// Initialize Feather Icons
feather.replace();

// ========== ADD PARFUME MODAL ==========
const modal = document.getElementById("parfume-modal");
const addBtn = document.getElementById("add-parfume-btn");
const closeBtn = document.getElementById("close-modal");
const cancelBtn = document.getElementById("cancel-modal");

addBtn.addEventListener("click", () => openModal(modal));
closeBtn.addEventListener("click", () => closeModal(modal));
cancelBtn.addEventListener("click", () => closeModal(modal));

modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal(modal);
});

// ========== EDIT PARFUME MODALS ==========
document.querySelectorAll('[id^="edit-parfume-btn-"]').forEach((editBtn) => {
    const id = editBtn.id.replace("edit-parfume-btn-", "");
    const modalEdit = document.getElementById(`parfume-modal-edit-${id}`);

    if (!modalEdit) return;

    const closeBtnEdit = modalEdit.querySelector(".close-modal-edit");
    const cancelBtnEdit = modalEdit.querySelector(".cancel-modal-edit");

    editBtn.addEventListener("click", () => openModal(modalEdit));

    [closeBtnEdit, cancelBtnEdit].forEach((btn) => {
        if (btn) {
            btn.addEventListener("click", () => closeModal(modalEdit));
        }
    });

    modalEdit.addEventListener("click", (e) => {
        if (e.target === modalEdit) closeModal(modalEdit);
    });
});

// ========== DELETE CONFIRMATION ==========
document.querySelectorAll(".delete-btn").forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const form = deleteBtn.closest(".delete-form");

        Swal.fire({
            title: "Delete Parfume?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel",
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
                    title: "Deleting parfume...",
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
const createParfumeBtn = document.getElementById("create_parfume_btn");
const saveEditBtns = document.querySelectorAll(".save-edit-btn");

createParfumeBtn.addEventListener("click", (e) => {
    const form = e.target.closest("form");
    if (form.checkValidity()) {
        Swal.fire({
            title: "Creating parfume...",
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }
});

saveEditBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const form = e.target.closest("form");
        if (form.checkValidity()) {
            Swal.fire({
                title: "Updating parfume...",
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
    }
});

// ========== AUTO-REFRESH FEATHER ICONS ==========
const observer = new MutationObserver(() => {
    feather.replace();
});

observer.observe(document.body, {
    childList: true,
    subtree: true,
});

// ========== IMPORT FORM SUBMISSION ==========
document.getElementById("importFile").addEventListener("change", () => {
    const form = document.getElementById("importForm");
    if (form && form.querySelector("#importFile").files.length > 0) {
        form.submit();
    }
});
