@push('styles')
    @vite('resources/css/register.css')
@endpush
<x-guest-layout>

    <div class="register-box">
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" style="height:50px;">
        </div>

        <h3>Registrasi Akun</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            @method('post')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('nama') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir"
                    value="{{ old('tanggal_lahir', $pasien->tanggal_lahir ?? '') }}"
                    max={{ date('Y-m-d', strtotime(now()->subYear(7))) }}>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <a href="{{ route('login') }}" class="back-btn">← Sudah punya akun? Masuk di sini</a>
    </div>
    </form>
</x-guest-layout>
