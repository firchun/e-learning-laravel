<div class="modal fade" id="create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="create-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">SKS</label>
                        <input type="text" class="form-control" id="sks_matkul" name="sks_matkul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-matkul" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="pilih_matkul" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="pilih-matkul-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Mata Kuliah</label>
                        <select class="form-control" name="id_matkul">
                            @foreach (App\Models\Matkul::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_matkul }} - {{ $item->kode_matkul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="pilih-matkul" class="btn btn-primary">Pilih</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="edit-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">SKS</label>
                        <input type="text" class="form-control" id="sks_matkul" name="sks_matkul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="update-matkul" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="edit-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">SKS</label>
                        <input type="text" class="form-control" id="sks_matkul" name="sks_matkul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="update-matkul" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="dosen-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dosen Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="dosen-form">
                    @csrf
                    <div class="mb-3">
                        <input type="hidden" name="id_matkul" id="id_matkul">
                        <div class="input-group">
                            <select name="id_user" class="form-control mx-3">
                                @foreach (App\Models\User::where('role', 'Dosen')->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - {{ $item->identity }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="button" id="save-dosen">Tambah</button>
                        </div>
                    </div>
                </form>
                <table class="table table-striped" id="datatable-dosen" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Nip</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
