document.addEventListener('DOMContentLoaded', function () {
    const cardFlip = document.querySelector('.card-flip');
    const btnEdit = document.getElementById('btnEdit');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const btnSave = document.getElementById('btnSave');
    const editForm = document.getElementById('editJadwalForm');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    btnEdit.addEventListener('click', () => {
        cardFlip.style.transform = 'rotateY(180deg)';
    });

    btnCancelEdit.addEventListener('click', () => {
        cardFlip.style.transform = 'rotateY(0deg)';
    });

    btnSave.addEventListener('click', () => {
        if (editForm.checkValidity()) {
            const formData = new FormData(editForm);
            fetch(editForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(async res => {
                    if (!res.ok) {
                        const errorText = await res.text();
                        throw new Error(errorText);
                    }
                    return res.json();
                })
                .then(response => {
                    const jadwal = response.jadwal;
                    const penguji = response.penguji; // <-- ambil langsung dari response

                    cardFlip.style.transform = 'rotateY(0deg)';
                    document.getElementById('tanggalDisplay').textContent = jadwal.tanggal;
                    document.getElementById('waktuDisplay').textContent =
                        jadwal.waktu_mulai.substring(0, 5) + ' - ' +
                        jadwal.waktu_selesai.substring(0, 5);
                    document.getElementById('ruanganDisplay').textContent = jadwal.ruangan
                        ?.lokasi || '-';

                    function getPenguji(peran) {
                        const peranDosen = jadwal?.sidang?.tugasAkhir?.peranDosenTa || [];
                        const p = peranDosen.find(p => p.peran === peran);
                        return p?.dosen?.user?.name || '-';
                    }

                    // Tampilkan nama dosen penguji dari response.penguji
                    ['penguji1', 'penguji2', 'penguji3', 'penguji4'].forEach(p => {
                        const el = document.getElementById(p + 'Display');
                        if (el) el.textContent = penguji[p] || '-';
                    });
                })
                .catch(error => {
                    console.error(error);
                    alert('Gagal menyimpan data:\n' + error.message);
                });

            fetch('/tandai-sidang/' + sidangId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        console.log('Sidang selesai:', data.message);

                        // Misal update tampilan tabel pasca sidang dengan data mahasiswa
                        // contoh: update tabel atau elemen di halaman
                        const mahasiswa = data.mahasiswa;
                        // tampilkan data mahasiswa sesuai kebutuhan, misal:
                        document.getElementById('namaMahasiswaPascaSidang').textContent = mahasiswa.name || '-';
                        // atau kamu bisa render tabel di sini

                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(err => alert('Error: ' + err.message || err));
        } else {
            alert('Form belum valid, tolong lengkapi data.');
        }
    });
});