@push('styles')
    @vite('resources/css/reset-password.css')
@endpush

<x-guest-layout>

    <div class="reset-box">
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" style="height:60px;">
        </div>

        <h3>Reset Kata Sandi</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            @method('post')

            <!-- Token Reset -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus
                    value="{{ old('email', $request->email) }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Reset Kata Sandi</button>
        </form>
    </div>

</x-guest-layout>
