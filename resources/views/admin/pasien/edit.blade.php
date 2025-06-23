<x-admin-layout :title="'Edit Pasien'">
    <div class="form-card">
        <h2>Edit Data Pasien</h2>
        <form method="POST" action="{{ route('admin.pasien.update', $patient->id) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $patient->user->name) }}" required>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', date('Y-m-d', strtotime($patient->tanggal_lahir))) }}" required
                    max={{ date('Y-m-d', strtotime(now()->subYear(7))) }}>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.pasien.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-admin-layout>
