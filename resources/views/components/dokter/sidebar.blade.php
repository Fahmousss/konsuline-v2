<div class="sidebar">
    <div class="sidebar-header">
        <h2>Menu Navigasi</h2>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('dokter.dashboard') }}" class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('dokter.konsultasi.index') }}"
            class="{{ request()->routeIs('dokter.konsultasi.index') ? 'active' : '' }}">
            <i class="fas fa-laptop-medical"></i> Konsultasi Online
        </a>
        <a href="{{ route('dokter.jadwal.index') }}"
            class="{{ request()->routeIs('dokter.jadwal.index') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i> Jadwal Praktik
        </a>
    </nav>
</div>
