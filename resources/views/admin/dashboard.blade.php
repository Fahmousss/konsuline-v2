@push('styles')
    @vite('resources/css/admin/dashboard.css')
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.kunjunganLabels = {!! json_encode($labels ?? []) !!};
        window.kunjunganData = {!! json_encode($data ?? []) !!};
    </script>
    @vite('resources/js/admin/dashboard.js')
@endpush

<x-admin-layout :title="'Dashboard Admin'">
    <div class="content-header">
        <h1>Selamat datang, {{ auth()->user()->username }}!</h1>
        <p>Ringkasan aktivitas dan statistik sistem</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card" onclick="window.location='{{ route('admin.pasien.index') }}'" style="cursor: pointer;">
            <div class="icon">
                <i class="fas fa-user-injured"></i>
            </div>
            <h3>Total Pasien</h3>
            <p>{{ number_format($totalPasien) }}</p>
            <div class="trend up">
                <i class="fas fa-arrow-up"></i>
                <span>12% dari bulan lalu</span>
            </div>
        </div>

        <a href="{{ route('admin.pembayaran.index') }}?filter=menunggu" style="text-decoration: none;">
            <div class="stat-card" style="cursor: pointer;">
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Permintaan Konsultasi</h3>
                <p>{{ $permintaanKonsultasi }}</p>
                <div class="trend up">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Perlu verifikasi</span>
                </div>
            </div>
        </a>

        <div class="stat-card" onclick="window.location='{{ route('admin.dokter.index') }}'" style="cursor: pointer;">
            <div class="icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h3>Dokter Aktif</h3>
            <p>{{ number_format($totalDokter) }}</p>
            <div class="trend">
                <i class="fas fa-equals"></i>
                <span>stabil</span>
            </div>
        </div>
    </div>

    <div class="section-card">
        <h2><i class="fas fa-chart-line"></i> Grafik Kunjungan Pasien</h2>
        <div class="chart-container">
            <canvas id="kunjunganChart"></canvas>
        </div>
    </div>

    <div class="grid-2-col"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
        <div class="section-card">
            <h2><i class="fas fa-bell"></i> Notifikasi Sistem</h2>
            <ul class="activity-list">
                <li>
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <strong>{{ $pasienBaruHariIni }} pasien baru</strong> terdaftar hari ini
                    </div>
                    <div class="activity-time">Hari ini</div>
                </li>
                <li>
                    <div class="activity-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="activity-content">
                        <strong>{{ $konsultasiBaru }} permintaan konsultasi</strong> menunggu verifikasi
                    </div>
                    <div class="activity-time">Hari ini</div>
                </li>
            </ul>
        </div>

        <div class="section-card">
            <h2><i class="fas fa-history"></i> Aktivitas Terbaru</h2>
            <ul class="activity-list">
                @foreach ($aktivitasTerbaru as $a)
                    <li>
                        <div class="activity-icon">
                            <i class="fas {{ $a['ikon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            {!! $a['pesan'] !!}
                        </div>
                        <div class="activity-time">{{ $a['waktu'] }}</div>
                    </li>
                @endforeach
                <li>
                    <div class="activity-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="activity-content">
                        Jadwal <strong>dr. {{ ['Budi', 'Ani', 'Citra'][rand(0, 2)] }}</strong> diperbarui
                    </div>
                    <div class="activity-time">2 jam lalu</div>
                </li>
            </ul>
        </div>
    </div>
</x-admin-layout>
