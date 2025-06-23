@push('scripts')
    @vite('resources/js/admin/manajemen-pasien.js')
@endpush

<x-admin-layout :title="'Manajemen Pasien'">
    <div class="content-header mb-4">
        <h1 class="fw-bold">Daftar Pasien</h1>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-md me-2"></i> Data Pasien
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Tanggal Lahir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient as $index => $pasien)
                            <tr>
                                <td>{{ $patient->firstItem() + $index }}</td>
                                <td>{{ $pasien->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.pasien.edit', $pasien->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $pasien->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data pasien.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $patient->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
