@push('styles')
    <style>
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

        @media (max-width: 768px) {
            .doctor-card {
                width: 100%;
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

        function dokterSearch(initialDoctors = []) {
            return {
                query: '',
                doctors: initialDoctors,
                allDoctors: initialDoctors,
                async search() {
                    if (this.query.length < 2) {
                        this.doctors = this.allDoctors;
                        return;
                    }

                    try {
                        const res = await fetch(`/pasien/dokter/search?q=${encodeURIComponent(this.query)}`);
                        const data = await res.json();
                        this.doctors = data;
                    } catch (e) {
                        console.error('Error fetching dokter:', e);
                    }
                },
            };
        }
    </script>
@endpush

<x-pasien-layout :title="'Cari Dokter'">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cari Dokter</h2>
        <div x-data="dokterSearch(@js(
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
))">
            <div class="row justify-content-center g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" x-model="query" @input.debounce.300ms="search" class="form-control"
                        placeholder="Cari nama dokter...">
                </div>
            </div>

            <template x-if="doctors.length === 0">
                <div class="no-results">
                    <p>Tidak ditemukan dokter sesuai pencarian.</p>
                </div>
            </template>

            <div class="card-container">
                <template x-for="(dokter, index) in doctors" :key="dokter.id">
                    <div class="doctor-card">
                        <template x-if="dokter.foto">
                            <img :src="dokter.foto" alt="Foto Dokter" class="doctor-avatar">
                        </template>
                        <template x-if="!dokter.foto">
                            <div class="doctor-placeholder"></div>
                        </template>

                        <div class="doctor-name" x-text="dokter.name"></div>
                        <div class="doctor-spesialis" x-text="dokter.specialty"></div>
                        <div class="rating-stars">★★★★☆</div>
                        <div class="doctor-harga"
                            x-text="dokter.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(dokter.harga) : 'Harga belum diatur'">
                        </div>


                        <div class="card-footer">
                            <button class="btn btn-outline-primary btn-sm"
                                @click="showDetail('jadwal-' + dokter.id)">Lihat Jadwal</button>

                            <a :href="`/pasien/dokter/${dokter.id}`" class="btn btn-success btn-sm">Konsultasi</a>
                        </div>

                        <div :id="'jadwal-' + dokter.id" class="card-toggle-content">
                            <strong>Jadwal:</strong> Senin - Jumat, 08:00 - 14:00
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
</x-pasien-layout>
