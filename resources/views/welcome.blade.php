@push('styles')
    @vite('resources/css/landing.css')
@endpush

<x-guest-layout :title="'Layanan E-Healthcare - Pendaftaran Online Rumah Sakit'">

    <header class="main-header">
        <div class="logo">
            <img src="{{ asset('images/logo-rs.png') }}" alt="Logo Rumah Sakit" />
        </div>
        @auth
            <div class="auth-buttons">
                <a href="{{ route(auth()->user()->role->redirectPath()) }}" class="btn-custom">Dashboard</a>
            </div>
        @else
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn-custom">Login</a>
            </div>
        @endauth
    </header>

    <main>
        <div class="hero-image">
            <img src="{{ asset('images/bg.png') }}" alt="Ilustrasi Layanan Digital" />
        </div>

        <section class="features">
            <h2>Fitur Unggulan Kami</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Pendaftaran Online</h3>
                    <p>Daftar janji temu dengan dokter pilihan Anda tanpa perlu antri di loket rumah sakit.</p>
                </div>
                <div class="feature-card">
                    <h3>Rekam Medis Digital</h3>
                    <p>Akses riwayat kesehatan Anda kapan saja dan di mana saja secara online.</p>
                </div>
                <div class="feature-card">
                    <h3>Konsultasi Dokter</h3>
                    <p>Layanan konsultasi dengan dokter spesialis melalui platform digital kami.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 SIMRS - Sistem Informasi Manajemen Rumah Sakit. All Rights Reserved.</p>
    </footer>
</x-guest-layout>
