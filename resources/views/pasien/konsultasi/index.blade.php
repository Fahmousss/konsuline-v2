@push('scripts')
    <script>
        function chatApp() {
            return {
                consultations: @json($consultations),
                selectedChat: null,
                messages: [],
                newMessage: '',
                user: @json(auth()->user()),
                review: {
                    rating: '',
                    comment: ''
                },

                init() {},

                selectChat(id) {
                    this.selectedChat = this.consultations.find(c => c.id === id);

                    this.fetchMessages();
                    if (this.selectedChat) {
                        Echo.private(`consultation.${this.selectedChat.id}`)
                            .listen('.message.sent', (e) => {
                                this.messages.push(e.message);
                                this.$nextTick(() => {
                                    const container = document.querySelector('.card-body.overflow-y-scroll');
                                    if (container) container.scrollTop = container.scrollHeight;
                                });
                            });
                    }
                },

                formatTime(datetime) {
                    const date = new Date(datetime);
                    return date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                get isLocked() {
                    const chat = this.selectedChat;
                    return chat.status === 'selesai' || !chat.payment || ['pending', 'menunggu verifikasi', 'gagal']
                        .includes(chat.payment?.status);
                },

                getPaymentMessage() {
                    const status = this.selectedChat?.payment?.status;
                    if (!status) return 'Silakan lakukan pembayaran untuk memulai konsultasi.';
                    if (status === 'pending') return 'Anda belum menyelesaikan pembayaran.';
                    if (status === 'menunggu verifikasi') return 'Pembayaran Anda sedang diverifikasi.';
                    if (status === 'gagal') return 'Pembayaran Anda gagal, silakan ulangi.';
                    return '';
                },

                fetchMessages() {
                    fetch(`/chat/messages/${this.selectedChat.id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.messages = data;

                            this.$nextTick(() => {
                                const container = document.querySelector('.card-body.overflow-y-scroll');
                                container.scrollTop = container.scrollHeight;
                            });
                        });
                },

                sendMessage() {
                    fetch(`/chat/send/${this.selectedChat.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            content: this.newMessage
                        })
                    }).then(() => {
                        this.newMessage = '';
                    });
                },

                showFinishModal() {
                    if (this.selectedChat.status !== 'berlangsung') {
                        alert('Konsultasi hanya bisa diselesaikan jika statusnya sedang berlangsung.');
                        return;
                    }

                    fetch(`/pasien/konsultasi/${this.selectedChat.id}/selesai`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menyelesaikan konsultasi');
                            return res.json();
                        })
                        .then(() => {
                            this.selectedChat.status = 'selesai';
                            const modal = new bootstrap.Modal(document.getElementById('finishModal'));
                            modal.show();
                        })
                        .catch(error => {
                            alert(error.message);
                        });
                },

                submitReview() {
                    if (!this.review.rating) {
                        alert('Rating wajib diisi.');
                        return;
                    }
                    console.log(this.review);


                    fetch(`/pasien/konsultasi/${this.selectedChat.id}/review`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(this.review)
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menyimpan ulasan');
                            return res.json();
                        })
                        .then(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('finishModal'));
                            modal.hide();
                            alert('Ulasan berhasil dikirim. Terima kasih!');
                        })
                        .catch(error => {
                            alert(error.message);
                        });
                }
            }
        }
    </script>
@endpush
<x-pasien-layout :title="'Konsultasi Online'" :header="'Konsultasi Online'">
    <div class="container py-4" x-data="chatApp()" x-init="init()">
        <div class="row">
            <div class="col-md-4">
                <div class="list-group">
                    <h5 class="mb-3">Daftar Konsultasi</h5>
                    @forelse ($consultations as $item)
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            @click.prevent="selectChat({{ $item->id }})">
                            <div>
                                <div class="fw-bold">{{ $item->doctor->user->name }}</div>
                                <small
                                    class="text-muted">{{ ucfirst($item->status === \App\Enums\StatusKonsultasi::VERIFIKASI ? 'Menunggu jawaban dokter' : $item->status->value) }}</small>
                            </div>
                            @if ($item->status === \App\Enums\StatusKonsultasi::PENDING && !$item->payment)
                                <span class="badge bg-warning">Bayar</span>
                            @elseif ($item->payment && $item->payment->status === \App\Enums\StatusPembayaran::PENDING)
                                <span class="badge bg-warning">Konfirmasi</span>
                            @elseif ($item->payment && $item->payment->status === \App\Enums\StatusPembayaran::VERIFIKASI)
                                <span class="badge bg-info">Menunggu Verifikasi</span>
                            @elseif ($item->payment && $item->payment->status === \App\Enums\StatusPembayaran::GAGAL)
                                <span class="badge bg-danger">Gagal</span>
                            @elseif ($item->status === \App\Enums\StatusKonsultasi::BERLANGSUNG)
                                <span class="badge bg-success">Online</span>
                            @elseif ($item->status === \App\Enums\StatusKonsultasi::SELESAI)
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </a>
                    @empty
                        <p class="text-muted">Belum ada konsultasi.</p>
                    @endforelse
                </div>
            </div>

            <div class="col-md-8" id="chat-container">
                <template x-if="selectedChat">
                    <div class="card h-100">
                        <template x-if="isLocked">

                            <div>
                                <div class="card-header bg-warning text-dark">
                                    <template x-if="!selectedChat.payment">
                                        <span>Pembayaran Belum Dibuat</span>
                                    </template>
                                    <template x-if="selectedChat.payment?.status === 'pending'">
                                        <span>Pembayaran Belum Selesai</span>
                                    </template>
                                    <template x-if="selectedChat.payment?.status === 'menunggu verifikasi'">
                                        <span>Pembayaran Menunggu Verifikasi</span>
                                    </template>
                                    <template x-if="selectedChat.payment?.status === 'gagal'">
                                        <span>Pembayaran Gagal</span>
                                    </template>
                                </div>
                                <div class="card-body text-center">
                                    <p x-text="getPaymentMessage()"></p>
                                    <template x-if="selectedChat.payment && selectedChat.payment?.status === 'pending'">
                                        <form method="GET"
                                            :action="'/pasien/pembayaran/' + selectedChat.payment.id + '/konfirmasi'">
                                            <button type="submit" class="btn btn-warning">
                                                Konfirmasi Pembayaran
                                            </button>
                                        </form>
                                    </template>
                                    <template x-if="!selectedChat.payment">
                                        <form method="GET" :action="'/pasien/konsultasi/create'">
                                            @csrf
                                            @method('get')
                                            <input type="hidden" name="consultation_id" :value="selectedChat.id">
                                            <button type="submit" class="btn btn-warning">
                                                Bayar
                                            </button>
                                        </form>
                                    </template>

                                </div>
                            </div>
                        </template>

                        <template x-if="!isLocked">
                            <div>
                                <div
                                    class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <span>Konsultasi dengan <span x-text="selectedChat.doctor.user.name"></span></span>
                                    <button class="btn btn-sm btn-success" @click="showFinishModal">Selesaikan</button>
                                </div>
                                <div class="card-body overflow-y-scroll" style="height: 400px;">
                                    <template x-for="(msg, index) in messages" :key="index">
                                        <div class="d-flex mb-2"
                                            :class="{
                                                'justify-content-end': msg.user.id === user.id,
                                                'justify-content-start': msg.user.id !== user.id
                                            }">
                                            <div class="p-2 rounded position-relative"
                                                :class="{
                                                    'bg-primary text-white': msg.user.id === user.id,
                                                    'bg-light border': msg.user.id !== user.id
                                                }"
                                                style="max-width: 70%">
                                                <div class="fw-bold mb-1"
                                                    x-text="msg.user.id !== user.id ? msg.user.name : 'Saya'">
                                                </div>
                                                <div x-text="msg.content"></div>
                                                <div class="text-end mt-1" style="font-size: 0.75rem; opacity: 0.7;"
                                                    x-text="formatTime(msg.created_at)"></div>
                                            </div>
                                        </div>
                                    </template>

                                </div>
                                <div class="card-footer">
                                    <form @submit.prevent="sendMessage" class="d-flex gap-2">
                                        <input type="text" x-model="newMessage" class="form-control"
                                            placeholder="Tulis pesan...">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-paper-plane"></i></button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!selectedChat">
                    <div class="card text-center p-5 text-muted">
                        <p>Pilih salah satu dokter untuk membuka ruang konsultasi.</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Ulasan -->
        <div class="modal fade" id="finishModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selesaikan Konsultasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <div class="d-flex gap-1">
                                <template x-for="i in 5" :key="i">
                                    <svg @click="review.rating = i"
                                        :class="{ 'text-warning': i <= review.rating, 'text-muted': i > review.rating }"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16" role="button">
                                        <path
                                            d="M3.612 15.443c-.396.198-.86-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696 2.036-4.287c.197-.416.73-.416.927 0l2.036 4.287 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.114.443-.35.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Ulasan</label>
                            <textarea class="form-control" rows="3" x-model="review.comment"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" @click="submitReview">Kirim</button>
                    </div>
                </div>
            </div>
        </div>


    </div>


</x-pasien-layout>
