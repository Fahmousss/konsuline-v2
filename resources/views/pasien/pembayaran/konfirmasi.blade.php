<x-pasien-layout :title="'Konfirmasi Pembayaran'">
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Konfirmasi Pembayaran</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                @if (isset($payment))
                    <p><strong>Dokter:</strong> {{ $payment->consultation->doctor->user->name }}</p>

                    <p><strong>Harga Konsultasi:</strong> Rp
                        {{ number_format($payment->jumlah - 3000, 0, ',', '.') }}</p>
                    <p><strong>Biaya Layanan:</strong> Rp 3.000</p>
                    <hr>
                    <p><strong>Total:</strong> Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                    <p><strong>Status Pembayaran:</strong>
                        @if ($payment->status === \App\Enums\StatusPembayaran::BERHASIL)
                            <span class="badge bg-success">Sukses</span>
                        @elseif($payment->status === \App\Enums\StatusPembayaran::VERIFIKASI)
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @else
                            <span class="badge bg-secondary">Belum Bayar</span>
                        @endif
                    </p>

                    <hr>

                    @if (is_null($payment->bukti_pembayaran))
                        <form action="{{ route('pasien.pembayaran.update', $payment->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="konsultasi_id" value="{{ $payment->consultation->id }}">

                            <div class="mb-3">
                                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (jpg, png,
                                    pdf)</label>
                                <input type="file" name="bukti_pembayaran" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success">
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @else
                        <p><strong>Bukti Pembayaran:</strong></p>
                        <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank">Lihat
                            Bukti</a>
                    @endif
                @else
                    <div class="alert alert-danger">Data tidak ditemukan.</div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-pasien-layout>
