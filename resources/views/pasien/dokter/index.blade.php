@push('styles')
    <style>
        .specialty-selection {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid #e9ecef;
        }

        .specialty-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .specialty-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #007bff;
        }

        .specialty-card.selected {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }

        .specialty-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #007bff;
        }

        .specialty-card.selected .specialty-icon {
            color: white;
        }

        .doctor-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s;
            background-color: #fff;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }

        .doctor-avatar {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 3px solid #f1f1f1;
        }

        .doctor-placeholder {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #999;
        }

        .doctor-name {
            font-size: 1.05rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.3rem;
        }

        .doctor-spesialis {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.2rem;
        }

        .doctor-harga {
            font-weight: 600;
            color: #28a745;
            font-size: 1rem;
        }

        .badge-status {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #fff;
        }

        .badge-offline {
            background-color: #dc3545;
        }

        .rating-stars {
            color: #f1c40f;
            font-size: 0.85rem;
            margin: 5px 0;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .card-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .card-toggle-content {
            display: none;
            font-size: 0.875rem;
            color: #555;
            margin-top: 10px;
        }

        .card-toggle-content.active {
            display: block;
        }

        .no-results {
            text-align: center;
            margin-top: 2rem;
            color: #888;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .doctor-card {
                width: 100%;
            }

            .specialty-card {
                margin: 0.25rem;
            }
        }

        @media (min-width: 769px) {
            .doctor-card {
                width: 280px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showDetail(id) {
            document.querySelectorAll('.card-toggle-content').forEach(el => el.classList.remove('active'));
            document.getElementById(id).classList.add('active');
        }

        function dokterApp() {
            return {
                selectedSpecialty: @js($selectedSpecialty ? $selectedSpecialty->id : null),
                selectedSpecialtyName: @js($selectedSpecialty ? $selectedSpecialty->nama : ''),
                specialtyQuery: '',
                specialties: @js(
    $specialties->map(function ($s) {
        return [
            'id' => $s->id,
            'nama' => $s->nama,
        ];
    }),
),
                filteredSpecialties: @js(
    $specialties->map(function ($s) {
        return [
            'id' => $s->id,
            'nama' => $s->nama,
        ];
    }),
),
                query: '',
                doctors: @js(
    $doctors->map(function ($d) {
        return [
            'id' => $d->id,
            'name' => $d->user->name,
            'email' => $d->user->email,
            'specialty' => $d->specialty->nama ?? '-',
            'foto' => $d->foto ? asset('storage/' . $d->foto) : null,
            'harga' => $d->harga_konsultasi,
        ];
    }),
),
                allDoctors: @js(
    $doctors->map(function ($d) {
        return [
            'id' => $d->id,
            'name' => $d->user->name,
            'email' => $d->user->email,
            'specialty' => $d->specialty->nama ?? '-',
            'foto' => $d->foto ? asset('storage/' . $d->foto) : null,
            'harga' => $d->harga_konsultasi,
        ];
    }),
),

                filterSpecialties() {
                    if (this.specialtyQuery.length === 0) {
                        this.filteredSpecialties = this.specialties;
                    } else {
                        this.filteredSpecialties = this.specialties.filter(specialty =>
                            specialty.nama.toLowerCase().includes(this.specialtyQuery.toLowerCase())
                        );
                    }
                },

                selectSpecialty(specialtyId, specialtyName) {
                    this.selectedSpecialty = specialtyId;
                    this.selectedSpecialtyName = specialtyName;
                    this.query = '';
                    this.loadDoctorsBySpecialty();
                },

                async loadDoctorsBySpecialty() {
                    if (!this.selectedSpecialty) {
                        this.doctors = [];
                        return;
                    }

                    try {
                        // Redirect to URL with specialty parameter to maintain pagination
                        window.location.href = `/pasien/dokter?specialty_id=${this.selectedSpecialty}`;
                    } catch (e) {
                        console.error('Error loading doctors:', e);
                    }
                },

                async search() {
                    if (!this.selectedSpecialty) {
                        return;
                    }

                    if (this.query.length < 2) {
                        this.doctors = this.allDoctors;
                        return;
                    }

                    try {
                        const res = await fetch(
                            `/pasien/dokter/search?q=${encodeURIComponent(this.query)}&specialty_id=${this.selectedSpecialty}`
                            );
                        const data = await res.json();
                        this.doctors = data;
                    } catch (e) {
                        console.error('Error fetching doctors:', e);
                    }
                },
            };
        }
    </script>
@endpush

<x-pasien-layout :title="'Cari Dokter'">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cari Dokter</h2>

        <div x-data="dokterApp()">
            <!-- Specialty Selection -->
            <div class="specialty-selection">
                <h4 class="mb-3 text-center">Pilih Spesialisasi</h4>

                <!-- Search Bar for Specialty -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" x-model="specialtyQuery" @input="filterSpecialties()"
                                class="form-control" placeholder="Cari spesialisasi...">
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <template x-for="specialty in filteredSpecialties" :key="specialty.id">
                        <div class="col-md-3 col-sm-6">
                            <div class="specialty-card" :class="{ 'selected': selectedSpecialty == specialty.id }"
                                @click="selectSpecialty(specialty.id, specialty.nama)">
                                <div class="specialty-icon">🩺</div>
                                <h6 class="mb-0" x-text="specialty.nama"></h6>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- No specialty found message -->
                <template x-if="filteredSpecialties.length === 0 && specialtyQuery">
                    <div class="text-center mt-3">
                        <p class="text-muted">Tidak ditemukan spesialisasi dengan kata kunci "<span
                                x-text="specialtyQuery"></span>"</p>
                    </div>
                </template>
            </div>

            <!-- Doctor Search and List (only show if specialty is selected) -->
            <template x-if="selectedSpecialty">
                <div>
                    <!-- Search Bar -->
                    <div class="row justify-content-center g-3 mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" x-model="query" @input.debounce.300ms="search"
                                    class="form-control" :placeholder="'Cari dokter ' + selectedSpecialtyName + '...'">
                            </div>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <template x-if="doctors.length === 0 && selectedSpecialty">
                        <div class="no-results">
                            <div class="empty-state-icon">👨‍⚕️</div>
                            <h5>Tidak ada dokter ditemukan</h5>
                            <p
                                x-text="query ? 'Tidak ditemukan dokter dengan nama \"' + query + '\" untuk spesialisasi ' + selectedSpecialtyName : 'Belum ada dokter untuk spesialisasi ' + selectedSpecialtyName">
                            </p>
                        </div>
                    </template>

                    <!-- Doctor Cards -->
                    <div class="card-container">
                        <template x-for="(dokter, index) in doctors" :key="dokter.id">
                            <div class="doctor-card">
                                <template x-if="dokter.foto">
                                    <img :src="dokter.foto" alt="Foto Dokter" class="doctor-avatar">
                                </template>
                                <template x-if="!dokter.foto">
                                    <div class="doctor-placeholder">👨‍⚕️</div>
                                </template>

                                <div class="doctor-name" x-text="dokter.name"></div>
                                <div class="doctor-spesialis" x-text="dokter.specialty"></div>
                                <div class="rating-stars">★★★★☆</div>
                                <div class="doctor-harga"
                                    x-text="dokter.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(dokter.harga) : 'Harga belum diatur'">
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-outline-primary btn-sm"
                                        @click="showDetail('jadwal-' + dokter.id)">
                                        Lihat Jadwal
                                    </button>
                                    <a :href="`/pasien/dokter/${dokter.id}`" class="btn btn-success btn-sm">
                                        Konsultasi
                                    </a>
                                </div>

                                <div :id="'jadwal-' + dokter.id" class="card-toggle-content">
                                    <strong>Jadwal:</strong> Senin - Jumat, 08:00 - 14:00
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Initial State (no specialty selected) -->
            <template x-if="!selectedSpecialty">
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h5>Pilih Spesialisasi Dokter</h5>
                    <p>Silakan pilih spesialisasi dokter terlebih dahulu untuk melihat daftar dokter yang tersedia.</p>
                </div>
            </template>
        </div>

        <!-- Pagination (only show if specialty is selected and doctors exist) -->
        @if ($selectedSpecialty && $doctors->count() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $doctors->appends(['specialty_id' => request('specialty_id')])->links() }}
            </div>
        @endif
    </div>
</x-pasien-layout>
