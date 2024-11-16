// Confirm before deletion
document.addEventListener("DOMContentLoaded", function () {
    const deleteLinks = document.querySelectorAll(".delete-link");

    deleteLinks.forEach(function (link) {
        link.addEventListener("click", function (event) {
            const confirmed = confirm("Apakah Anda yakin ingin menghapus data ini?");
            if (!confirmed) {
                event.preventDefault(); // Batal hapus jika tidak dikonfirmasi
            }
        });
    });
});

// Display notifications
function showAlert(message, type = "success") {
    const alertBox = document.createElement("div");
    alertBox.className = `alert alert-${type}`;
    alertBox.innerText = message;

    // Tambahkan ke halaman
    document.body.insertBefore(alertBox, document.body.firstChild);

    // Hilangkan notifikasi setelah beberapa detik
    setTimeout(() => {
        alertBox.remove();
    }, 3000);
}