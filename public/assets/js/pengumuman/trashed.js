document.addEventListener('DOMContentLoaded', function() {
    let forceDeleteId = null;

    const modalForceDeleteAllEl = document.getElementById('modalForceDeleteAll');
    const modalForceDeleteSingleEl = document.getElementById('modalForceDeleteSingle');

    const modalForceDeleteAll = modalForceDeleteAllEl ? new bootstrap.Modal(modalForceDeleteAllEl) : null;
    const modalForceDeleteSingle = modalForceDeleteSingleEl ? new bootstrap.Modal(
        modalForceDeleteSingleEl) : null;

    const btnHapusSemua = document.getElementById('btnHapusSemua');
    if (btnHapusSemua && modalForceDeleteAll) {
        btnHapusSemua.addEventListener('click', function() {
            modalForceDeleteAll.show();
        });
    }

    const btnConfirmForceDeleteAll = document.getElementById('btnConfirmForceDeleteAll');
    if (btnConfirmForceDeleteAll) {
        btnConfirmForceDeleteAll.addEventListener('click', function() {
            const form = document.getElementById('formDeleteAll');
            if (form) form.submit();
        });
    }

    const btnConfirmForceDeleteSingle = document.getElementById('btnConfirmForceDeleteSingle');
    if (btnConfirmForceDeleteSingle && modalForceDeleteSingle) {
        btnConfirmForceDeleteSingle.addEventListener('click', function() {
            if (!forceDeleteId) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengumuman/force-delete/${forceDeleteId}`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value =
            'Yl5L8KVCAG6Iic2gzEzHlaD1t95MFfnfbYGekvi2'; // Ganti dengan {{ csrf_token() }} di Blade

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        });
    }

    document.querySelectorAll('.btn-force-delete').forEach(button => {
        button.addEventListener('click', function() {
            forceDeleteId = this.dataset.id;
            console.log('ID untuk hapus permanen:', forceDeleteId);
            if (modalForceDeleteSingle) modalForceDeleteSingle.show();
        });
    });
});