import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

window.Pusher = Pusher;

const token = localStorage.getItem('auth_token');
console.log('🔥 ECHO FILE VERSION 123');
console.log('🔥 AUTH ENDPOINT SHOULD BE: /api/broadcasting/auth');

console.log(
    'Echo initialized:',
    token ? 'TOKEN EXISTS' : 'TOKEN MISSING'
);
window.Echo = new Echo({
    broadcaster: 'reverb',

    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: import.meta.env.VITE_REVERB_HOST,

    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,

    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,

    forceTLS: false,

    enabledTransports: ['ws', 'wss'],

    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {

                console.log('🔐 Authorizing channel:', channel.name);
                console.log('🔐 Socket ID:', socketId);

                axios.post(
                    '/api/broadcasting/auth',
                    {
                        socket_id: socketId,
                        channel_name: channel.name,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('auth_token') ?? ''}`,
                            Accept: 'application/json',
                        },
                    }
                )
                .then((response) => {

                    console.log(
                        '✅ Broadcast auth success:',
                        response.status
                    );

                    callback(false, response.data);
                })
                .catch((error) => {

                    console.error(
                        '❌ Broadcast auth failed:',
                        error.response?.status
                    );

                    console.error(
                        '❌ Broadcast auth response:',
                        error.response?.data
                    );

                    callback(true, error);
                });
            },
        };
    },
});

const conversationId = 1;

window.Echo
    .private(`conversation.${conversationId}`)
    .subscribed(() => {
        console.log(
            '✅ Successfully subscribed to conversation:',
            conversationId
        );
    })
    .error((error) => {
        console.error(
            '❌ Subscription error:',
            error
        );
    })
    .listen('.typing.started', (event) => {
        console.log('🟢 TYPING STARTED:', event);
    })
    .listen('.typing.stopped', (event) => {
        console.log('🔴 TYPING STOPPED:', event);
    });
