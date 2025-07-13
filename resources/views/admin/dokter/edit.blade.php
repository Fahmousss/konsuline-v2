<x-admin-layout>
    <div class="form-card">
        <h2>Edit Data Dokter</h2>
        <form method="POST" action="{{ route('admin.dokter.update', $doctor->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="form-group">
                <label>Nama Dokter</label>
                <input type="text" name="nama" value="{{ old('nama', $doctor->user->name) }}" required>
            </div>

            <div class="form-group">
                <label>Spesialis</label>
                <select name="spesialis_id" required>
                    <option value="">-- Pilih Spesialis --</option>
                    @foreach ($specialties as $spesialis)
                        <option value="{{ $spesialis->id }}"
                            {{ $doctor->specialty_id === $spesialis->id ? 'selected' : '' }}>
                            {{ $spesialis->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Ganti Foto (opsional)</label>
                <input type="file" name="foto">
                @if ($doctor->foto)
                    <div class="current-photo" style="display: flex; align-items: center; gap: 10px;">
                        <small>Foto saat ini:</small>
                        <img src="{{ asset('storage/' . $doctor->foto) }}" alt="Foto Dokter"
                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dokter.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>

</x-admin-layout>
