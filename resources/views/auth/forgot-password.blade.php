@push('styles')
    @vite('resources/css/forgot-password.css')
@endpush
<x-guest-layout>
    <div class="forgot-box">
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" style="height:60px;">
        </div>
        <h3>Lupa Kata Sandi</h3>

        <p class="text-muted text-center mb-4" style="font-size: 14px;">
            Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            @method('post')
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus
                    value="{{ old('email') }}">
            </div>
            <button type="submit" class="btn btn-primary w-100">Kirim Tautan Reset</button>
        </form>

        <a href="{{ route('login') }}" class="back-btn">← Kembali ke Login</a>
    </div>
</x-guest-layout>
