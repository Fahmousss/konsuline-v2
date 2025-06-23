<x-admin-layout :title="'Tambah Dokter'">
    <div class="form-card">
        <h2>Tambah Dokter Baru</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.dokter.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Spesialis</label>
                <select name="spesialis_id">
                    <option value="">-- Pilih Spesialis --</option>
                    @foreach ($spesialis as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Foto Dokter (Opsional)</label>
                <input type="file" name="foto">
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dokter.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</x-admin-layout>
