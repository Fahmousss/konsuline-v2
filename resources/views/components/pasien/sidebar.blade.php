<div class="sidebar" aria-label="Menu navigasi pasien">
    <div class="sidebar-header">
        <h2>Menu Navigasi</h2>
    </div>
    <nav class="sidebar-menu" role="navigation" aria-labelledby="menu-title">
        <a href="{{ route('pasien.dashboard') }}" class="{{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('pasien.dokter.index') }}"
            class="{{ request()->routeIs('pasien.dokter.index') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i> Daftar Dokter
        </a>
        <a href="{{ route('pasien.konsultasi.index') }}"
            class="{{ request()->routeIs('pasien.konsultasi.index') ? 'active' : '' }}">
            <i class="fas fa-stethoscope"></i> Konsultasi Dokter
        </a>
        <a href="{{ route('pasien.konsultasi.riwayat') }}"
            class="{{ request()->routeIs('pasien.konsultasi.riwayat') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Riwayat Konsultasi
        </a>

    </nav>
</div>
