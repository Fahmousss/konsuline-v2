<div class="header">
    <div class="header-left">
        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo Rumah Sakit">
        <span>Panel Dokter</span>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar"> {{ auth()->user() ? substr(auth()->user()->username, 0, 1) : 'D' }}</div>
            <span class="username">{{ auth()->user()->username ?? 'Dokter' }}</span>
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        @endauth
    </div>
</div>
