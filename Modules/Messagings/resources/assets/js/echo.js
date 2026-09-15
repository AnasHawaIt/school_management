import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// أثناء التطوير فقط
Pusher.logToConsole = true;

const token = localStorage.getItem('auth_token');

console.log('🔥 ECHO INITIALIZING');
console.log(
    '🔐 Auth token:',
    token ? 'TOKEN EXISTS' : 'TOKEN MISSING'
);

console.log(
    '🔐 Broadcast auth endpoint:',
    '/api/broadcasting/auth'
);

console.log(
    '🔌 Reverb host:',
    import.meta.env.VITE_REVERB_HOST
);

console.log(
    '🔌 Reverb port:',
    import.meta.env.VITE_REVERB_PORT
);

console.log(
    '🔑 Reverb key:',
    import.meta.env.VITE_REVERB_APP_KEY
        ? 'KEY EXISTS'
        : 'KEY MISSING'
);

window.Echo = new Echo({
    broadcaster: 'reverb',

    key: import.meta.env.VITE_REVERB_APP_KEY,

    wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',

    wsPort: Number(
        import.meta.env.VITE_REVERB_PORT || 8080
    ),

    wssPort: Number(
        import.meta.env.VITE_REVERB_PORT || 8080
    ),

    forceTLS:
        (import.meta.env.VITE_REVERB_SCHEME || 'http') ===
        'https',

    enabledTransports: ['ws', 'wss'],

    /**
     * Authentication for private / presence channels.
     */
    authorizer: (channel) => {
        return {
            authorize: (socketId, callback) => {

                console.log(
                    '🔐 Authorizing channel:',
                    channel.name
                );

                console.log(
                    '🔐 Socket ID:',
                    socketId
                );

                window.axios
                    .post(
                        '/api/broadcasting/auth',
                        {
                            socket_id: socketId,
                            channel_name: channel.name,
                        },
                        {
                            headers: {
                                Authorization:
                                    `Bearer ${localStorage.getItem('auth_token') ?? ''}`,

                                Accept:
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },
                        }
                    )
                    .then((response) => {

                        console.log(
                            '✅ Broadcast auth success:',
                            response.status
                        );

                        callback(
                            false,
                            response.data
                        );
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

                        callback(
                            true,
                            error
                        );
                    });
            },
        };
    },
});

console.log('✅ Echo initialized');
console.log('🌐 window.Echo:', window.Echo);


/*
|--------------------------------------------------------------------------
| Announcement
|--------------------------------------------------------------------------
|
| Keep your existing Announcement real-time listeners.
|
*/

window.Echo
    .channel('announcement')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO ANNOUNCEMENT CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ ANNOUNCEMENT CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.announcement.published',
        (event) => {
            console.log(
                '📢 ANNOUNCEMENT PUBLISHED:',
                event
            );
        }
    )

    .listen(
        '.announcement.expired',
        (event) => {
            console.log(
                '⏰ ANNOUNCEMENT EXPIRED:',
                event
            );
        }
    )

    .listen(
        '.announcement.created',
        (event) => {
            console.log(
                '🆕 ANNOUNCEMENT CREATED:',
                event
            );
        }
    )

    .listen(
        '.announcement.scheduled',
        (event) => {
            console.log(
                '📢⏰🆕 ANNOUNCEMENT SCHEDULED:',
                event
            );
        }
    )

    .listen(
        '.announcement.updated',
        (event) => {
            console.log(
                '✏️ ANNOUNCEMENT UPDATED:',
                event
            );
        }
    )

    .listen(
        '.announcement.deleted',
        (event) => {
            console.log(
                '🗑️ ANNOUNCEMENT DELETED:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Messaging - Presence
|--------------------------------------------------------------------------
|
| Existing Messaging test.
|
*/

const conversationId = 1;

window.Echo
    .join(`conversation.${conversationId}`)

    .here((users) => {
        console.log(
            '🟢 USERS CURRENTLY ONLINE:',
            users
        );
    })

    .joining((user) => {
        console.log(
            '🟢 USER JOINED:',
            user
        );
    })

    .leaving((user) => {
        console.log(
            '⚪ USER LEFT:',
            user
        );
    })

    .error((error) => {
        console.error(
            '❌ PRESENCE ERROR:',
            error
        );
    });


/*
|--------------------------------------------------------------------------
| Messaging - Private Channel
|--------------------------------------------------------------------------
*/

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
            '❌ Conversation subscription error:',
            error
        );
    })

    .listen(
        '.typing.started',
        (event) => {
            console.log(
                '🟢 TYPING STARTED:',
                event
            );
        }
    )

    .listen(
        '.typing.stopped',
        (event) => {
            console.log(
                '🔴 TYPING STOPPED:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Library
|--------------------------------------------------------------------------
|
| We will activate the Library subscription during the
| current Reverb test.
|
*/

window.Echo
    .channel('library.books')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO LIBRARY BOOKS CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ LIBRARY BOOKS CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.book.updated',
        (event) => {

            console.log(
                '📚 BOOK UPDATED - REAL TIME:',
                event
            );

        }
    );

console.log(
    '👂 Listening for Library Book events...'
);
