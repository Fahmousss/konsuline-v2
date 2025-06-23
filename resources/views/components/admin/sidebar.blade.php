<!-- resources/views/components/admin/sidebar.blade.php -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Menu Navigasi</h2>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.pasien.index') }}"
            class="{{ request()->routeIs('admin.pasien.index') ? 'active' : '' }}">
            <i class="fas fa-user-injured"></i>
            Manajemen Pasien
        </a>
        <a href="{{ route('admin.dokter.index') }}"
            class="{{ request()->routeIs('admin.dokter.index') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i>
            Manajemen Dokter
        </a>
        <a href="{{ route('admin.pembayaran.index') }}"
            class="{{ request()->routeIs('admin.pembayaran.index') ? 'active' : '' }}">
            <i class="fas fa-file-medical"></i>
            Pembayaran
        </a>
    </nav>
</div>
