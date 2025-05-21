document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalHapus');
    const form = modal ? modal.querySelector('#formForceDelete') : null;
    const btnConfirm = document.getElementById('confirmHapus');

    if (modal && form && btnConfirm) {
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const url = `/admin/pengumuman/${id}/force-delete`;
            form.setAttribute('action', url);
        });

        btnConfirm.addEventListener('click', function () {
            form.submit();
        });
    }
});
