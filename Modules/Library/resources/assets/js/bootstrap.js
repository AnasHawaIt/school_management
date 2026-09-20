import _ from 'lodash';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window._ = _;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

// Enable Pusher/Reverb debugging during development.
// Remove or disable this in production.
Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'reverb',

    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',

    wsPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),

    wssPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),

    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',

    enabledTransports: ['ws', 'wss'],
});

console.log('Laravel Echo initialized:', window.Echo);
