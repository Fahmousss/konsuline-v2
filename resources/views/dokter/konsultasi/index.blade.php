<x-dokter-layout :title="'Konsultasi Online'" :header="'Pesan & Konsultasi'">
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
                                <div class="fw-bold">{{ $item->patient->user->name }}</div>
                                <small
                                    class="text-muted">{{ ucfirst(str_replace('_', ' ', $item->status->value)) }}</small>
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
                        <template x-if="selectedChat.status === 'menunggu verifikasi'">
                            <div class="card">
                                <div class="card-header bg-info text-dark">
                                    Konsultasi Belum Dimulai
                                </div>
                                <div class="card-body text-center">
                                    <p>Pasien telah melakukan pembayaran dan menunggu dokter memulai sesi konsultasi.
                                    </p>
                                    <button @click="mulaiKonsultasi" class="btn btn-primary">Mulai Konsultasi</button>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedChat.status === 'berlangsung'">
                            <div>
                                <div class="card-header bg-primary text-white">
                                    Konsultasi dengan <span x-text="selectedChat.patient.user.name"></span>
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
                        <p>Pilih salah satu pasien untuk membuka ruang konsultasi.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                consultations: @json($consultations),
                selectedChat: null,
                messages: [],
                newMessage: '',
                user: @json(auth()->user()),
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                init() {},
                formatTime(datetime) {
                    const date = new Date(datetime);
                    return date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },


                selectChat(id) {

                    this.selectedChat = this.consultations.find(c => c.id === id);
                    console.log(this.selectedChat);

                    this.fetchMessages();

                    if (this.selectedChat) {
                        Echo.private(`consultation.${this.selectedChat.id}`)
                            .listen('.message.sent', (e) => {
                                this.messages.push(e.message);
                                // console.log(e.message);
                                this.$nextTick(() => {
                                    const container = document.querySelector('.card-body.overflow-y-scroll');
                                    if (container) container.scrollTop = container.scrollHeight;
                                });
                            });
                    }
                },

                mulaiKonsultasi() {
                    fetch(`/dokter/konsultasi/${this.selectedChat.id}/mulai`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.selectedChat.status = 'berlangsung';
                            }
                        });
                },

                fetchMessages() {
                    fetch(`/chat/messages/${this.selectedChat.id}`)
                        .then(res => res.json())
                        .then(data => {

                            this.messages = data;

                            this.$nextTick(() => {
                                const container = document.querySelector('.card-body.overflow-y-scroll');
                                if (container) container.scrollTop = container.scrollHeight;
                            });
                        });

                },

                sendMessage() {
                    fetch(`/chat/send/${this.selectedChat.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            content: this.newMessage
                        })
                    }).then(() => {
                        this.newMessage = '';
                        // this.fetchMessages();
                    });
                }
            }
        }
    </script>
</x-dokter-layout>
