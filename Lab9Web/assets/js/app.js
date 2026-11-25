/**
 * assets/js/app.js
 * Konfirmasi penghapusan data sebelum formulir POST dikirimkan ke server.
 */
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.js-confirm-delete');
    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const namaItem = form.getAttribute('data-nama') || 'data ini';
            const yakin = window.confirm('Apakah Anda yakin ingin menghapus "' + namaItem + '" secara permanen?');
            if (!yakin) {
                event.preventDefault();
            }
        });
    });
});
