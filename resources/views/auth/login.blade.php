@push('styles')
    @vite('resources/css/login.css')
@endpush
<x-guest-layout>
    <div class="login-box">
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" style="height:60px;">
        </div>
        <h3>Login</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            @method('post')
            <div class="mb-3">
                <label for="login" class="form-label">Username atau email</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}"
                    required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat Saya
                </label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>

        <a href="{{ route('password.request') }}" class="back-btn">Lupa password?</a>
        <a href="{{ route('register') }}" class="back-btn">Belum punya akun? Daftar dulu ya!</a>
    </div>
</x-guest-layout>
