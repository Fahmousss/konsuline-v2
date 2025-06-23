@push('styles')
    <style>
        :root {
            --primary-color: #2c4bb8;
            --secondary-color: #f8f9fa;
            --text-dark: #333;
            --text-light: #6c757d;
            --border-color: #e0e0e0;
            --success-color: #28a745;
            --light-bg: #f5f7fa;
        }

        .konsultasi-container {
            max-width: 100%;
            padding: 16px;
            margin: 20px auto;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            font-size: 0.85rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #1e3a8a);
            color: #fff;
            padding: 16px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .card-subtitle {
            font-size: 0.9rem;
            font-weight: 400;
        }

        .dokter-info {
            display: flex;
            align-items: center;
            background: rgba(44, 75, 184, 0.05);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .dokter-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .dokter-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .dokter-spesialis {
            font-size: 0.85rem;
            color: var(--primary-color);
        }

        .dokter-harga {
            font-size: 0.9rem;
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            padding: 6px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 4px;
        }

        .form-section {
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--primary-color);
            border-bottom: 1px solid rgba(44, 75, 184, 0.1);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 8px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .form-select,
        .form-control {
            font-size: 0.85rem;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .info-box {
            font-size: 0.8rem;
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 12px;
            border-radius: 6px;
            margin-top: 12px;
            display: none;
        }

        .qris-image {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 10px;
            border-radius: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #1e3a8a);
            color: #fff;
            border: none;
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            background: transparent;
        }

        @media (min-width: 768px) {
            .konsultasi-container {
                max-width: 500px;
            }

            .dokter-info {
                flex-wrap: nowrap;
            }

            .action-buttons {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metode = document.getElementById('metode_pembayaran');
            const qris = document.getElementById('qris-info');
            const transfer = document.getElementById('transfer-info');

            metode.addEventListener('change', function() {
                qris.style.display = 'none';
                transfer.style.display = 'none';

                if (this.value === 'qris') qris.style.display = 'block';
                else if (this.value === 'transfer') transfer.style.display = 'block';
            });

            if (metode.value) metode.dispatchEvent(new Event('change'));
        });
    </script>
@endpush


<x-pasien-layout :title="'Pendaftaran'">
    <div class="konsultasi-container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Pendaftaran Konsultasi</h1>
                <p class="card-subtitle">Silakan lanjutkan ke pembayaran</p>
            </div>

            <div class="card-body">
                <!-- Info Dokter -->
                <div class="dokter-info">
                    <img src="{{ asset('storage/foto_dokter/' . $consultation->doctor->foto) }}" alt="Foto Dokter"
                        class="dokter-photo">
                    <div>
                        <h2 class="dokter-name">{{ $consultation->doctor->user->name ?? '-' }}</h2>
                        <p class="dokter-spesialis">{{ $consultation->doctor->specialty->nama ?? '-' }}</p>
                        <div class="dokter-harga">Rp
                            {{ number_format($consultation->doctor->harga_konsultasi ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('pasien.konsultasi.store') }}">
                    @csrf
                    @method('post')
                    <input type="hidden" name="doctor_id" value="{{ $consultation->doctor->id }}">
                    <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                    <!-- Tampilkan Jadwal yang Dipilih -->
                    <div class="form-section">
                        <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Jadwal Terpilih</h3>
                        <p><strong>Hari:</strong> {{ $consultation->hari }}</p>
                        <p><strong>Jam:</strong> {{ $consultation->jam_konsultasi }}</p>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="form-section">
                        <h3 class="section-title"><i class="fas fa-credit-card"></i> Metode Pembayaran</h3>
                        <div class="mb-3">
                            <label class="form-label">Pilih Metode</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>

                        <div id="qris-info" class="info-box">
                            <strong>Pembayaran QRIS</strong>
                            <p>Scan QR berikut:</p>
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Code" class="qris-image">
                            <p><strong>Nominal:</strong> Rp
                                {{ number_format($consultation->doctor->harga_konsultasi ?? 0, 0, ',', '.') }}</p>
                            <p><strong>Kode:</strong> KONSUL-{{ date('YmdHis') }}</p>
                        </div>

                        <div id="transfer-info" class="info-box">
                            <strong>Pembayaran Transfer Bank</strong>
                            <p>Transfer ke:</p>
                            <ul>
                                <li>BRI - 1234567890 a.n RS E-Healthcare</li>
                                <li>Mandiri - 9876543210 a.n RS E-Healthcare</li>
                            </ul>
                            <p><strong>Nominal:</strong> Rp
                                {{ number_format($consultation->doctor->harga_konsultasi ?? 0, 0, ',', '.') }}</p>
                            <p><strong>Kode:</strong> KONSUL-{{ date('YmdHis') }}</p>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="action-buttons">
                        <a href="{{ route('pasien.dokter.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-pasien-layout>
