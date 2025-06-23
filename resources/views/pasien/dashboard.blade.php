<x-pasien-layout :title="'Dashboard Pasien'" :header="'Dashboard Pasien'" :description="'Selamat datang kembali, ' . auth()->user()->name">
    <div class="container py-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Konsultasi</h5>
                        <p class="card-text fs-4">{{ $summary['total_konsultasi'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h4>Informasi Akun</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nama:</strong> {{ $user->user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->user->email }}</li>
                <li class="list-group-item"><strong>Terdaftar Sejak:</strong>
                    {{ $user->user->created_at->format('d M Y') }}
                </li>
            </ul>
        </div>
    </div>
</x-pasien-layout>
