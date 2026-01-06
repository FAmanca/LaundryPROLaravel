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
