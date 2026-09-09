import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

console.log('🔥 ECHO FILE VERSION 124');

window.Echo = new Echo({
    broadcaster: 'reverb',

    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: import.meta.env.VITE_REVERB_HOST,

    wsPort: Number(import.meta.env.VITE_REVERB_PORT ?? 8080),

    wssPort: Number(import.meta.env.VITE_REVERB_PORT ?? 8080),

    forceTLS: false,

    enabledTransports: ['ws', 'wss'],
});

console.log('✅ Echo initialized');
console.log('🔌 Reverb host:', import.meta.env.VITE_REVERB_HOST);
console.log('🔌 Reverb port:', import.meta.env.VITE_REVERB_PORT);

window.Echo
    .channel('announcement')
    .subscribed(() => {
        console.log('🟢 SUBSCRIBED TO ANNOUNCEMENT CHANNEL');
    })
    .error((error) => {
        console.error('❌ ANNOUNCEMENT CHANNEL ERROR:', error);
    })
    .listen('.announcement.published', (event) => {
        console.log('📢 ANNOUNCEMENT PUBLISHED:', event);
    })
    .listen('.announcement.expired', (event) => {
        console.log('⏰ ANNOUNCEMENT EXPIRED:', event);
    })
    .listen('.announcement.created', (event) => {
        console.log('🆕 ANNOUNCEMENT CREATED:', event);
    })
    .listen('.announcement.scheduled', (event) => {
        console.log('📢⏰🆕 ANNOUNCEMENT SCHEDULED:', event);
    })
    .listen('.announcement.updated', (event) => {
        console.log('✏️ ANNOUNCEMENT UPDATED:', event);
    })
    .listen('.announcement.deleted', (event) => {
        console.log('🗑️ ANNOUNCEMENT DELETED:', event);
    });

console.log('👂 Listening for announcement events...');
