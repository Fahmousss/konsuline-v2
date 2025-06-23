@php
    use App\Enums\Hari;
@endphp

@push('scripts')
    <script>
        window.doctorScheduleData = @json($jadwal);
        window.hariOptionsData = @json(array_map(fn($h) => $h->value, \App\Enums\Hari::cases()));
    </script>
    <script>
        function jadwalManager(initialJadwals = [], hariOptions = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
        ]) {
            return {
                jadwals: initialJadwals,
                hariOptions: hariOptions,
                form: {
                    hari: '',
                    jam_mulai: '',
                    jam_selesai: ''
                },
                editForm: {
                    id: null,
                    hari: '',
                    jam_mulai: '',
                    jam_selesai: ''
                },
                createErrors: [],
                editErrors: [],
                successMessage: '',
                async simpanJadwal() {
                    this.createErrors = [];
                    this.successMessage = '';
                    try {
                        const res = await fetch(`/dokter/jadwal`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.form)
                        });

                        if (!res.ok) {
                            const error = await res.json();
                            if (error.errors) {
                                for (const field in error.errors) {
                                    this.createErrors.push(...error.errors[field]);
                                }
                            } else if (error.message) {
                                this.createErrors.push(error.message);
                            }
                            return;
                        }

                        const data = await res.json();
                        this.jadwals.push(data);
                        this.form = {
                            hari: '',
                            jam_mulai: '',
                            jam_selesai: ''
                        };
                        this.successMessage = 'Jadwal berhasil ditambahkan';
                    } catch (e) {
                        this.createErrors = ['Terjadi kesalahan saat menyimpan jadwal.'];
                        console.error(e);
                    }
                },

                showEdit(jadwal) {
                    this.editForm = {
                        ...jadwal
                    };
                    const modalEl = document.getElementById('editJadwalModal');
                    const modal = new bootstrap.Modal(modalEl);

                    modalEl.removeAttribute('aria-hidden');
                    modal.show();

                    setTimeout(() => {
                        modalEl.querySelector('input, select, textarea')?.focus();
                    }, 200);
                },

                async updateJadwal() {
                    this.editErrors = [];
                    this.successMessage = '';
                    try {
                        const res = await fetch(`/dokter/jadwal/${this.editForm.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.editForm)
                        });

                        if (!res.ok) {
                            const error = await res.json();
                            if (error.errors) {
                                for (const field in error.errors) {
                                    this.editErrors.push(...error.errors[field]);
                                }
                            } else if (error.message) {
                                this.editErrors.push(error.message);
                            }
                            return;
                        }

                        const updated = await res.json();
                        const index = this.jadwals.findIndex(j => j.id === updated.id);
                        if (index !== -1) this.jadwals[index] = updated;

                        this.successMessage = 'Jadwal berhasil diperbarui';
                        bootstrap.Modal.getInstance(document.getElementById('editJadwalModal')).hide();
                    } catch (e) {
                        this.editErrors = ['Terjadi kesalahan saat mengupdate jadwal.'];
                        console.error(e);
                    }
                },

                async hapusJadwal(id) {
                    if (!confirm('Yakin ingin menghapus jadwal ini?')) return;

                    try {
                        const res = await fetch(`/dokter/jadwal/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });

                        if (!res.ok) throw new Error('Gagal hapus');
                        setTimeout(() => location.reload(), 1500);
                        alert('Jadwal berhasil dihapus');
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus');
                    }
                }
            };
        }
    </script>
@endpush

<x-dokter-layout :title="'Jadwal Konsultasi Saya'">
    <div class="container mt-4" x-data="jadwalManager(window.doctorScheduleData, window.hariOptionsData)">
        <h2 class="mb-3">Tambah Jadwal</h2>
        <div x-show="successMessage" class="alert alert-success" x-text="successMessage"></div>
        <div x-show="createErrors.length" class="alert alert-danger" x-html="createErrors.join('<br>')"></div>

        <form @submit.prevent="simpanJadwal" class="row g-2 mb-4">
            <div class="col-md-3">
                <select class="form-select" x-model="form.hari" required>
                    <option value="">-- Pilih Hari --</option>
                    <template x-for="hari in hariOptions" :key="hari">
                        <option :value="hari" x-text="hari"></option>
                    </template>
                </select>
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control" x-model="form.jam_mulai" required>
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control" x-model="form.jam_selesai" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100">Simpan Jadwal</button>
            </div>
        </form>

        <h4>Jadwal Saat Ini</h4>
        <template x-if="jadwals.length === 0">
            <div class="alert alert-info">Belum ada jadwal yang ditambahkan.</div>
        </template>

        <div class="table-responsive" x-show="jadwals.length > 0">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="jadwal in jadwals" :key="jadwal.id">
                        <tr>
                            <td x-text="jadwal.hari"></td>
                            <td x-text="jadwal.jam_mulai"></td>
                            <td x-text="jadwal.jam_selesai"></td>
                            <td>
                                <button class="btn btn-sm btn-warning" @click="showEdit(jadwal)">Edit</button>
                                <button class="btn btn-sm btn-danger" @click="hapusJadwal(jadwal.id)">Hapus</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Modal Edit Jadwal -->
        <div class="modal fade" id="editJadwalModal" tabindex="-1" data-bs-keyboard="false"
            aria-labelledby="editJadwalModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div x-show="editErrors.length" class="alert alert-danger" x-html="editErrors.join('<br>')">
                        </div>

                        <form @submit.prevent="updateJadwal()">
                            <div class="mb-3">
                                <label>Hari</label>
                                <select class="form-select" x-model="editForm.hari" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <template x-for="hari in hariOptions" :key="hari">
                                        <option :value="hari" x-text="hari"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Jam Mulai</label>
                                <input type="time" class="form-control" x-model="editForm.jam_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label>Jam Selesai</label>
                                <input type="time" class="form-control" x-model="editForm.jam_selesai" required>
                            </div>
                            <button class="btn btn-primary w-100">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dokter-layout>
