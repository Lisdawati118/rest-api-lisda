@extends('layouts.app')
@section('content')
<h3>Data Mahasiswa</h3>
<button class="btn btn-primary mb-3" id="btn-tambah">Tambah Mahasiswa</button>
<table class="table table-bordered" id="table-mahasiswa">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Jurusan</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="ModalAddLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formMahasiswa" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalAddLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="edit_nim">
      <div class="mb-2">
        <label for="">NIM</label>
        <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM">
      </div>
        <div class="mb-2">
            <label for="">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama">
      </div>
        <div class="mb-2">
            <label for="">Jenis Kelamin</label>
            <select name="jk" id="jk" class="form-control">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
        </div>
        <div class="mb-2">
            <label for="">Jurusan</label>
            <select name="jurusan" id="jurusan" class="form-control">
                <option value="">Pilih Jurusan</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Mesin">Teknik Mesin</option>
                <option value="Teknik Elektro">Teknik Elektro</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Akuntansi">Akuntansi</option>
            </select>
        </div>
        
        <div class="mb-2">
            <label for="">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control"  rows="3" placeholder="Masukkan Alamat"></textarea>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-simpan">Save Data</button>
        <button type="button" class="btn btn-primary" id="btn-update">Edit Data</button>
      </div>
    </form>
  </div>
</div>

@endsection
@section('scripts')
<script>
    var table;
    $(document).ready(function() {
        table = $('#table-mahasiswa').DataTable({
            // processing: true,
            // serverSide: true,
            ajax: "/api/mahasiswa",
            columns: [
                { 
                    data: 'nim', 
                    name: 'nim' 
                },
                { 
                    data: 'nama', 
                    name: 'nama' 
                },
                { 
                    data: 'jk', 
                    name: 'jk' 
                },
                { 
                    data: 'tgl_lahir', 
                    name: 'tgl_lahir' 
                },
                { 
                    data: 'jurusan', 
                    name: 'jurusan' 
                },
                { 
                    data: 'alamat', 
                    name: 'alamat' 
                },
                { 
                    data: 'nim', 
                    render:function(nim) {
                        return `
                            <button class="btn btn-warning btn-sm btn-edit" data-id="${nim}">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${nim}">Hapus</button>
                        `;
                    }
                
                }
            ]
        });

        $('#btn-tambah').click(function() {
        $('#ModalAddLabel').text('Tambah Mahasiswa');
        $('#ModalAdd').modal('show');
        $('#formMahasiswa')[0].reset();
        $('#btn-simpan').show();
        $('#btn-update').hide();
        $('#edit_nim').val('');
        $('#nim').prop('readonly', false);
});

        function ambildataform() {
            return {
                nim: $('#nim').val(),
                nama: $('#nama').val(),
                jk: $('#jk').val(),
                tgl_lahir: $('#tgl_lahir').val(),
                jurusan: $('#jurusan').val(),
                alamat: $('#alamat').val()
            };
        }

        $('#btn-simpan').click(function() {
            var data = ambildataform();

            $.ajax({
                url: '/api/mahasiswa',
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#ModalAdd').modal('hide');
                    table.ajax.reload();
                    alert('Data berhasil disimpan');
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        $('#table-mahasiswa').on('click', '.btn-edit', function() {
            var nim = $(this).data('id');
            $.ajax({
                url: '/api/mahasiswa/' + nim,
                type: 'GET',
                success: function(data) {
                    $('#ModalAddLabel').text('Edit Mahasiswa');
                    $('#ModalAdd').modal('show');
                    $('#nim').val(data.nim).prop('readonly', true);
                    $('#nama').val(data.nama);
                    $('#jk').val(data.jk);
                    $('#tgl_lahir').val(data.tgl_lahir);
                    $('#jurusan').val(data.jurusan);
                    $('#alamat').val(data.alamat);
                    $('#btn-simpan').hide();
                    $('#btn-update').show();
                    $('#edit_nim').val(nim);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        $('#btn-update').click(function() {
            var nim = $('#edit_nim').val();
            var data = ambildataform();

            $.ajax({
                url: '/api/mahasiswa/' + nim,
                type: 'PUT',
                data: data,
                success: function(response) {
                    $('#ModalAdd').modal('hide');
                    table.ajax.reload();
                    alert('Data berhasil diupdate');
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        $('#table-mahasiswa').on('click', '.btn-delete', function() {
            var nim = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '/api/mahasiswa/' + nim,
                    type: 'DELETE',
                    success: function(response) {
                        table.ajax.reload();
                        alert('Data berhasil dihapus');
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            }
        });

    });
</script>
@endsection