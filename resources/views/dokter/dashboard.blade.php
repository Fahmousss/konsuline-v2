<x-dokter-layout :title="'Dashboard Dokter'" :header="'Dashboard Dokter'" :description="'Selamat datang kembali, ' . auth()->user()->name">
    <div class="container py-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Konsultasi</h5>
                        <p class="card-text fs-4">{{ $summary['total_konsultasi'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Review</h5>
                        <p class="card-text fs-4">{{ $summary['total_review'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan</h5>
                        <p class="card-text fs-4">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h4>Informasi Akun</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nama:</strong> {{ $dokter->user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $dokter->user->email }}</li>
                <li class="list-group-item"><strong>Spesialis:</strong> {{ $dokter->specialty->nama ?? '-' }}</li>
            </ul>
        </div>
    </div>
</x-dokter-layout>
