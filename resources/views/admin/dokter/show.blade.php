<x-admin-layout :title="'Detail Dokter - ' . $doctor->user->name">
    <div class="container mt-4">

        {{-- Informasi Akun --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Informasi Akun</h5>
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $doctor->user->name }}</p>
                <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
            </div>
        </div>

        {{-- Detail Dokter --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Detail Dokter</h5>
            </div>
            <div class="card-body">
                <p><strong>Spesialisasi:</strong> {{ $doctor->specialty->nama ?? '-' }}</p>
                <p><strong>Foto:</strong></p>
                @if ($doctor->foto)
                    <img src="{{ asset('storage/foto_dokter/' . $doctor->foto) }}" alt="Foto Dokter" class="img-thumbnail"
                        style="max-width: 200px;">
                @else
                    <p class="text-muted">Tidak ada foto.</p>
                @endif
            </div>
        </div>

        {{-- Jadwal Praktik --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Jadwal Praktik</h5>
            </div>
            <div class="card-body">
                @if ($doctor->doctorSchedule->isEmpty())
                    <p class="text-muted">Belum ada jadwal.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctor->doctorSchedule as $jadwal)
                                <tr>
                                    <td>{{ ucfirst($jadwal->hari->value) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Harga Layanan --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Harga Layanan</h5>
            </div>
            <div class="card-body">
                @if ($doctor->harga_konsultasi)
                    <p><strong>Konsultasi:</strong> Rp
                        {{ number_format($doctor->harga_konsultasi, 0, ',', '.') }}</p>
                @else
                    <p class="text-muted">Harga belum diatur.</p>
                @endif
            </div>
        </div>

        <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Dokter
        </a>

    </div>
</x-admin-layout>
