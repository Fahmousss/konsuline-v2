@push('scripts')
    <script>
        function dokterSearch(initialDoctors = []) {
            return {
                query: '',
                doctors: initialDoctors,
                passwordForm: {
                    id: null,
                    name: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: [],
                hargaForm: {
                    id: null,
                    name: '',
                    harga: ''
                },
                showHargaModal(dokter) {
                    this.hargaForm = {
                        id: dokter.id,
                        name: dokter.name,
                        harga: dokter.harga || ''
                    };
                    const modal = new bootstrap.Modal(document.getElementById('hargaModal'));
                    modal.show();
                },
                async ubahHarga() {
                    try {
                        const res = await fetch(`/admin/dokter/${this.hargaForm.id}/harga`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                harga: this.hargaForm.harga
                            })
                        });

                        if (!res.ok) {
                            const error = await res.json();
                            this.errors = [];
                            if (error.errors) {
                                for (const field in error.errors) {
                                    this.errors.push(...error.errors[field]);
                                }
                            } else if (error.message) {
                                this.errors.push(error.message);
                            }
                            return;
                        }

                        bootstrap.Modal.getInstance(document.getElementById('hargaModal')).hide();
                        alert('Harga berhasil diubah');
                    } catch (e) {
                        console.error(e);
                        alert('Gagal mengubah harga');
                    }
                },


                showPasswordModal(dokter) {
                    this.passwordForm = {
                        id: dokter.id,
                        name: dokter.name,
                        password: '',
                        password_confirmation: ''
                    };
                    const modal = new bootstrap.Modal(document.getElementById('passwordModal'));
                    modal.show();
                },
                async ubahPassword() {
                    try {
                        const res = await fetch(`/admin/dokter/${this.passwordForm.id}/ubah-password`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                password: this.passwordForm.password,
                                password_confirmation: this.passwordForm.password_confirmation
                            })
                        });

                        if (!res.ok) {
                            const error = await res.json();
                            if (error.errors) {
                                for (const field in error.errors) {
                                    this.errors.push(...error.errors[field]);
                                }
                            } else if (error.message) {
                                this.errors.push(error.message);
                            }
                            return;
                        }

                        bootstrap.Modal.getInstance(document.getElementById('passwordModal')).hide();
                        alert('Password berhasil diubah');
                    } catch (e) {
                        console.error(e);
                        alert('Gagal mengubah password');
                    }
                },


                async search() {
                    if (this.query.length < 2) {
                        this.doctors = initialDoctors;
                        return;
                    }

                    try {
                        const res = await fetch(`/admin/dokter/search?q=${encodeURIComponent(this.query)}`);
                        const data = await res.json();
                        this.doctors = data;
                    } catch (e) {
                        console.error('Error fetching dokter:', e);
                    }
                },

                async hapusDokter(id) {
                    if (!confirm('Yakin ingin menghapus dokter ini?')) return;

                    try {
                        const res = await fetch(`/admin/dokter/${id}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });

                        if (!res.ok) throw new Error('Gagal hapus');
                        setTimeout(() => location.reload(), 1500);
                        alert('Dokter berhasil dihapus');
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus');
                    }
                }
            };
        }
    </script>
@endpush

<x-admin-layout :title="'Manajemen Dokter'">
    <div class="container mt-4 ">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Dokter
        </a>

        <div x-data="dokterSearch(@js(
    $doctors->map(function ($d) {
        return [
            'id' => $d->id,
            'name' => $d->user->name,
            'email' => $d->user->email,
            'specialty' => $d->specialty->nama ?? '-',
            'foto' => $d->foto ? asset('storage/foto_dokter/' . $d->foto) : null,
        ];
    }),
))" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" x-model="query" @input.debounce.300ms="search" class="form-control"
                    placeholder="Cari nama dokter...">
            </div>


            <div class="section-card">
                <h2><i class="fas fa-user-md"></i> Data Dokter</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Spesialisasi</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(dokter, index) in doctors" :key="dokter.id">
                                <tr>
                                    <td x-text="index + 1"></td>
                                    <td x-text="dokter.name"></td>
                                    <td x-text="dokter.specialty || '-'"></td>
                                    <td>
                                        <template x-if="dokter.foto">
                                            <img :src="dokter.foto" class="rounded shadow-sm" style="width: 100px;">
                                        </template>
                                        <template x-if="!dokter.foto">
                                            <span class="text-muted">Belum ada</span>
                                        </template>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <button class="btn btn-sm btn-warning" @click="showPasswordModal(dokter)">
                                                Ubah Password
                                            </button>

                                            <a :href="`/admin/dokter/${dokter.id}`" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>

                                            <a :href="`/admin/dokter/${dokter.id}/edit`" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a :href="`/admin/dokter/${dokter.id}/jadwal`" class="btn btn-sm btn-info">
                                                <i class="fas fa-calendar-alt"></i> Jadwal
                                            </a>

                                            <button class="btn btn-sm btn-secondary" @click="showHargaModal(dokter)">
                                                <i class="fas fa-money-bill-wave"></i> Harga
                                            </button>


                                            <button @click="hapusDokter(dokter.id)" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            </template>
                            <tr x-show="doctors.length === 0">
                                <td colspan="5" class="text-center text-muted">Belum ada hasil pencarian.</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Ubah Password -->
            <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" x-data>
                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div x-show="errors.length" class="alert alert-danger" x-html="errors.join('<br>')">
                            </div>
                            <form @submit.prevent="ubahPassword()">
                                <div class="mb-3">
                                    <label>Nama Dokter</label>
                                    <input type="text" class="form-control" x-model="passwordForm.name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Password Baru</label>
                                    <input type="password" class="form-control" x-model="passwordForm.password"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control"
                                        x-model="passwordForm.password_confirmation" required>
                                </div>
                                <button class="btn btn-primary w-100">Simpan Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ubah Harga -->
            <div class="modal fade" id="hargaModal" tabindex="-1" aria-labelledby="hargaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" x-data>
                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Harga Konsultasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div x-show="errors.length" class="alert alert-danger" x-html="errors.join('<br>')"></div>
                            <form @submit.prevent="ubahHarga()">
                                <div class="mb-3">
                                    <label>Nama Dokter</label>
                                    <input type="text" class="form-control" x-model="hargaForm.name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Harga Konsultasi (Rp)</label>
                                    <input type="number" min="0" class="form-control" x-model="hargaForm.harga"
                                        required>
                                </div>
                                <button class="btn btn-primary w-100">Simpan Harga</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-admin-layout>
