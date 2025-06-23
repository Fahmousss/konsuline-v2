@push('styles')
    <style>
        :root {
            --primary-color: #2c4bb8;
            --bg-dark: #1a1a2e;
        }

        body {
            background: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }
    </style>
@endpush

<x-guest-layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="mb-3 text-center">Verifikasi Email Anda</h4>

                        <p class="text-muted">
                            Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan
                            mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email, kami akan
                            dengan senang hati mengirimkannya kembali.
                        </p>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success">
                                Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    Keluar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
