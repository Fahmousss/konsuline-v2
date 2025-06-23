<x-admin-layout :title="'Daftar Pembayaran Konsultasi'">
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Pembayaran Konsultasi</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="popupAlert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaran as $i => $p)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $p->patient->user->name ?? '-' }}</td>
                            <td>{{ $p->consultation->doctor->user->name ?? '-' }}</td>
                            <td class="text-center">
                                @if ($p->bukti_pembayaran)
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#verifikasiModal{{ $p->id }}">
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match ($p->status->value) {
                                        'berhasil' => 'success',
                                        'menunggu verifikasi' => 'warning',
                                        'gagal' => 'danger',
                                        'pending' => 'secondary',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} text-uppercase px-3 py-2">
                                    {{ $p->status->value }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($p->bukti_pembayaran)
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#verifikasiModal{{ $p->id }}">
                                        Verifikasi
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Verifikasi --}}
                        <div class="modal fade" id="verifikasiModal{{ $p->id }}" tabindex="-1"
                            aria-labelledby="verifikasiModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="verifikasiModalLabel{{ $p->id }}">Verifikasi
                                            Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p><strong>Pasien:</strong> {{ $p->patient->user->name ?? '-' }}</p>
                                        <p><strong>Dokter:</strong> {{ $p->consultation->doctor->user->name ?? '-' }}
                                        </p>

                                        <img src="{{ asset('storage/' . $p->bukti_pembayaran) }}"
                                            class="img-fluid rounded mb-3" style="max-height: 400px;">

                                        <form method="POST" action="{{ route('admin.pembayaran.update', $p->id) }}">
                                            @csrf
                                            @method('patch')
                                            <div class="mb-3">
                                                <label for="statusSelect{{ $p->id }}" class="form-label">Pilih
                                                    Status</label>
                                                <select name="status" id="statusSelect{{ $p->id }}"
                                                    class="form-select" required>
                                                    <option value="pending"
                                                        {{ $p->status->value == 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="menunggu verifikasi"
                                                        {{ $p->status->value == 'menunggu verifikasi' ? 'selected' : '' }}>
                                                        Menunggu Verifikasi</option>
                                                    <option value="gagal"
                                                        {{ $p->status->value == 'gagal' ? 'selected' : '' }}>Gagal
                                                    </option>
                                                    <option value="berhasil"
                                                        {{ $p->status->value == 'berhasil' ? 'selected' : '' }}>
                                                        Berhasil
                                                    </option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100">Simpan Status</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End Modal --}}
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada hasil pembayaran.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            window.onload = function() {
                const alertBox = document.getElementById('popupAlert');
                if (alertBox) {
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                    }, 3500);
                }
            };
        </script>
    @endpush
</x-admin-layout>
