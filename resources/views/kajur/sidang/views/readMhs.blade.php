<h1 class="text-center mb-4 fw-bold">Mahasiswa yang Akan Sidang</h1>

<div class="mb-4 d-flex justify-content-center">
  <input type="text" id="searchBar" class="form-control form-control-lg shadow-sm w-50" placeholder="Cari nama atau NIM...">
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle text-center shadow-sm bg-white">
    <thead class="table-dark">
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama Mahasiswa</th>
        <th scope="col">NIM</th>
        <th scope="col">Judul Skripsi</th>
        <th scope="col">Dosen Penguji</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Erland Agsya A</td>
        <td>2311083007</td>
        <td>Implementasi Judol</td>
        <td>Dr. Hendri Yusmedi. S.Pd</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Nose</td>
        <td>2311083007</td>
        <td>Implementasi Judol</td>
        <td>Dr. Hendri Yusmedi. S.Pd</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Footersss</td>
        <td>2311083007</td>
        <td>Implementasi Judol</td>
        <td>Dr. Prabowo. S.Pd</td>
      </tr>
      <tr>
        <td>4</td>
        <td>Altamis</td>
        <td>2311083007</td>
        <td>Implementasi Judol</td>
        <td>Dr. Anis. S.Pd</td>
      </tr>
      <tr>
        <td>5</td>
        <td>azda</td>
        <td>2311083007</td>
        <td>Implementasi Judol</td>
        <td>Dr. Tulus. S.Pd</td>
      </tr>
      <tr>
        <td>6</td>
        <td>Rina</td>
        <td>2311081234</td>
        <td>Implementasi Judol</td>
        <td>Dr. Tulul. S.Pd</td>
      </tr>
      <!-- Tambahkan data mahasiswa lain di sini -->
    </tbody>
  </table>
</div>

<script>
  document.getElementById('searchBar').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
      let nama = row.cells[1].textContent.toLowerCase();
      let nim = row.cells[2].textContent.toLowerCase();
      if (nama.includes(filter) || nim.includes(filter)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
</script>
