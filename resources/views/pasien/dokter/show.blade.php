@push('styles')
    <style>
        .slot-button {
            padding: 8px 16px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        .slot-button:hover {
            background-color: #e2e6ea;
        }

        .slot-button.selected {
            background-color: #0d6efd;
            color: white;
            border-color: #0a58ca;
        }

        .slot-button.disabled {
            background-color: #dee2e6;
            cursor: not-allowed;
            color: #6c757d;
        }
    </style>
@endpush

@push('scripts')
    @push('scripts')
        <script>
            const semuaSlot = @json($slots);
            const booked = @json($booked);

            function tampilkanJam() {
                const hari = document.getElementById('hari').value;
                const jamContainer = document.getElementById('jam-options');
                const hiddenInput = document.getElementById('jam_konsultasi');

                jamContainer.innerHTML = '';
                hiddenInput.value = '';

                if (semuaSlot[hari]) {
                    semuaSlot[hari].forEach(jam => {
                        const isBooked = booked.includes(jam);
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = jam;
                        btn.className = 'slot-button' + (isBooked ? ' disabled' : '');
                        btn.disabled = isBooked;

                        if (!isBooked) {
                            btn.addEventListener('click', () => {
                                document.querySelectorAll('.slot-button').forEach(b => b.classList.remove(
                                    'selected'));
                                btn.classList.add('selected');
                                hiddenInput.value = jam;
                            });
                        }

                        jamContainer.appendChild(btn);
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const form = document.querySelector('form');
                form.addEventListener('submit', (e) => {
                    const hari = document.getElementById('hari').value;
                    const jam = document.getElementById('jam_konsultasi').value;

                    if (!hari || !jam) {
                        e.preventDefault();
                        alert('Silakan pilih hari dan jam konsultasi terlebih dahulu.');
                    }
                });
            });
        </script>
    @endpush
@endpush

<x-pasien-layout :title="'Informasi Dokter'" :header="'Informasi Dokter'">
    <div class="container py-4">
        {{-- Kartu Profil Dokter --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center">
                <img src="{{ asset('storage/' . $doctor->foto) }}" class="rounded-circle me-3" width="90" height="90"
                    alt="Foto Dokter">
                <div>
                    <h5 class="mb-1">{{ $doctor->user->name ?? 'Nama tidak tersedia' }}</h5>
                    <p class="mb-0 text-muted">{{ $doctor->specialty->nama ?? 'Spesialis tidak tersedia' }}</p>
                    <span class="badge bg-success mt-1">Rp
                        {{ number_format($doctor->harga_konsultasi, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Kartu Jadwal Praktik + Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <strong>Jadwal Praktik & Pendaftaran</strong>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="GET" action="{{ route('pasien.konsultasi.create') }}">
                    @csrf
                    @method('get')
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    <!--Pilih Hari-->
                    <div class="mb-3">
                        <label for="hari" class="form-label">Pilih Hari</label>
                        <select name="hari" id="hari" class="form-select" required onchange="tampilkanJam()">
                            <option value="">-- Pilih Hari --</option>
                            @foreach (array_keys($slots) as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Jam Konsultasi --}}
                    <div class="mb-3">
                        <label class="form-label">Pilih Jam</label>
                        <div id="jam-options" class="d-flex flex-wrap gap-2"></div>
                        <input type="hidden" name="jam_konsultasi" id="jam_konsultasi" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Daftar Konsultasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-pasien-layout>
