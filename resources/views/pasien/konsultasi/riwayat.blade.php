<x-pasien-layout>
    <div class="container py-5">
        <h2 class="mb-4">Riwayat Konsultasi</h2>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Dokter</th>
                        <th>Pembayaran</th>
                        <th>Review</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultation as $index => $konsultasi)
                        <tr>
                            <td>{{ $konsultasi->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $konsultasi->doctor->user->name ?? '-' }}</td>
                            <td>
                                @if ($konsultasi->payment)
                                    <span class="badge bg-success">Sudah Bayar</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                @if ($konsultasi->doctor->review)
                                    ⭐ {{ $konsultasi->doctor->review->rating }}
                                @else
                                    <span class="text-muted">Belum diulas</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#detail-{{ $index }}" aria-expanded="false"
                                    aria-controls="detail-{{ $index }}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="detail-{{ $index }}">
                            <td colspan="5" class="bg-light">
                                <div class="p-3">
                                    <p><strong>Dokter:</strong> {{ $konsultasi->doctor->user->name ?? '-' }}</p>
                                    <p><strong>Spesialis:</strong> {{ $konsultasi->doctor->specialty->nama ?? '-' }}</p>

                                    @if ($konsultasi->doctor->review)
                                        <p><strong>Review:</strong> "{{ $konsultasi->doctor->review->comment }}"</p>
                                    @endif

                                    @if ($konsultasi->payment)
                                        <p><strong>Metode Pembayaran:</strong>
                                            {{ $konsultasi->payment->metode_pembayaran ?? '-' }}</p>
                                        <p><strong>Jumlah:</strong>
                                            Rp{{ number_format($konsultasi->payment->jumlah, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada riwayat konsultasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-pasien-layout>
