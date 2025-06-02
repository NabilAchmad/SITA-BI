<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1>ACC Judul Tugas Akhir</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Judul Tugas Akhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="judulTable">
                        @foreach ($judulTAs as $judul)
                            <tr>
                                <td>{{ $judul->mahasiswa->nama ?? 'N/A' }}</td>
                                <td>{{ $judul->judul }}</td>
                                <td id="status-{{ $judul->id }}">
                                    @if ($judul->status == 'Disetujui')
                                        <span class="text-success">Disetujui</span>
                                    @elseif ($judul->status == 'Ditolak')
                                        <span class="text-danger">Ditolak</span>
                                    @else
                                        <span class="text-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-success" onclick="accJudul({{ $judul->id }})"
                                        @if($judul->status == 'Disetujui') disabled @endif>ACC Judul</button>
                                    <button class="btn btn-warning" onclick="tolakJudul({{ $judul->id }})"
                                        @if($judul->status == 'Ditolak') disabled @endif>Tolak Judul</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function accJudul(id) {
        fetch(`/ketua-prodi/judulTA/approve/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                const statusCell = document.getElementById('status-' + id);
                statusCell.innerHTML = 'Disetujui';
                statusCell.classList.remove('text-warning', 'text-danger');
                statusCell.classList.add('text-success');
                // Disable buttons after approval
                const accButton = statusCell.parentElement.querySelector('.btn-success');
                const rejectButton = statusCell.parentElement.querySelector('.btn-warning');
                accButton.disabled = true;
                rejectButton.disabled = true;

                // Add approved judul to the ACC table
                const accTableBody = document.querySelector('#accTable tbody');
                const row = document.createElement('tr');
                row.id = 'acc-row-' + id;

                // Get current date and time formatted as d-m-Y H:i
                const now = new Date();
                const formattedDate = now.getDate().toString().padStart(2, '0') + '-' +
                    (now.getMonth() + 1).toString().padStart(2, '0') + '-' +
                    now.getFullYear() + ' ' +
                    now.getHours().toString().padStart(2, '0') + ':' +
                    now.getMinutes().toString().padStart(2, '0');

                // Get judul and pengaju from the existing row
                const judul = statusCell.parentElement.querySelector('td:nth-child(2)').innerText;
                const pengaju = statusCell.parentElement.querySelector('td:nth-child(1)').innerText;

                row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${judul}</td>
                <td>${pengaju}</td>
            `;
                accTableBody.appendChild(row);

                alert(data.message);
            })
            .catch(error => {
                alert('Terjadi kesalahan saat meng-ACC judul.');
                console.error('Error:', error);
            });
    }

    function tolakJudul(id) {
        fetch(`/ketua-prodi/judulTA/reject/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                const statusCell = document.getElementById('status-' + id);
                statusCell.innerHTML = 'Ditolak';
                statusCell.classList.remove('text-success');
                statusCell.classList.add('text-danger');
                // Disable buttons after rejection
                const accButton = statusCell.parentElement.querySelector('.btn-success');
                const rejectButton = statusCell.parentElement.querySelector('.btn-warning');
                accButton.disabled = true;
                rejectButton.disabled = true;
                alert(data.message);
            })
            .catch(error => {
                alert('Terjadi kesalahan saat menolak judul.');
                console.error('Error:', error);
            });
    }
</script>