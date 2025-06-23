import './bootstrap';
import './echo'
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'local',
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     forceTLS: false,
//     disableStats: true,
// });
