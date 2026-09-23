import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// أثناء التطوير فقط
Pusher.logToConsole = true;

const token = localStorage.getItem('auth_token');

console.log('🔥 ECHO FILE VERSION 126');

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
     *
     * Sanctum token is sent as:
     *
     * Authorization: Bearer <auth_token>
     */
    authorizer: (channel) => {
        return {
            authorize: (socketId, callback) => {

                const authToken =
                    localStorage.getItem('auth_token');

                console.log(
                    '🔐 Authorizing channel:',
                    channel.name
                );

                console.log(
                    '🔐 Socket ID:',
                    socketId
                );

                console.log(
                    '🔐 Token:',
                    authToken
                        ? 'TOKEN EXISTS'
                        : 'TOKEN MISSING'
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
                                    `Bearer ${authToken ?? ''}`,

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

                        console.log(
                            '✅ Channel authorized:',
                            channel.name
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

                        console.error(
                            '❌ Channel:',
                            channel.name
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

console.log(
    '🌐 window.Echo:',
    window.Echo
);


/*
|--------------------------------------------------------------------------
| Announcement
|--------------------------------------------------------------------------
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

console.log(
    '👂 Listening for announcement events...'
);


/*
|--------------------------------------------------------------------------
| Library Books
|--------------------------------------------------------------------------
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
                '✏️ BOOK UPDATED - REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.book.created',
        (event) => {
            console.log(
                '➕ BOOK CREATED - REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.book.deleted',
        (event) => {
            console.log(
                '🗑️ BOOK DELETED - REAL TIME:',
                event
            );
        }
    );

console.log(
    '👂 Listening for Library Book events...'
);


/*
|--------------------------------------------------------------------------
| Library Book Copies
|--------------------------------------------------------------------------
*/

window.Echo
    .channel('library.book-copies')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO LIBRARY BOOK COPIES CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ LIBRARY BOOK COPIES CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.book-copy.created',
        (event) => {
            console.log(
                '➕ BOOK COPY CREATED:',
                event
            );
        }
    )

    .listen(
        '.book-copy.updated',
        (event) => {
            console.log(
                '✏️ BOOK COPY UPDATED:',
                event
            );
        }
    )

    .listen(
        '.book-copy.deleted',
        (event) => {
            console.log(
                '🗑️ BOOK COPY DELETED:',
                event
            );
        }
    )

    .listen(
        '.book-copy.status-changed',
        (event) => {
            console.log(
                '🔄 BOOK COPY STATUS CHANGED:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Core Permissions
|--------------------------------------------------------------------------
*/

window.Echo
    .channel('core.permissions')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO CORE PERMISSIONS CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ CORE PERMISSIONS CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.permission.changed',
        (event) => {
            console.log(
                '🔐 PERMISSION CHANGED:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Core Settings
|--------------------------------------------------------------------------
*/

window.Echo
    .channel('core.settings')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO CORE SETTINGS CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ CORE SETTINGS CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.setting.changed',
        (event) => {
            console.log(
                '⚙️ SETTING CHANGED:',
                event
            );
        }
    )

    .listen(
        '.settings.changed',
        (event) => {
            console.log(
                '⚙️ SETTINGS CHANGED:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Core Roles - Private
|--------------------------------------------------------------------------
*/

window.Echo
    .private('admin.roles')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO ADMIN ROLES CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ ADMIN ROLES CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.role.changed',
        (event) => {
            console.log(
                '🔐 ROLE CHANGED REAL TIME:',
                event
            );
        }
    );


/*
|--------------------------------------------------------------------------
| Core Users - Private
|--------------------------------------------------------------------------
*/

window.Echo
    .private('users')

    .subscribed(() => {
        console.log(
            '🟢 SUBSCRIBED TO USERS CHANNEL'
        );
    })

    .error((error) => {
        console.error(
            '❌ USERS CHANNEL ERROR:',
            error
        );
    })

    .listen(
        '.user.created',
        (event) => {
            console.log(
                '👤 USER CREATED REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.user.updated',
        (event) => {
            console.log(
                '✏️ USER UPDATED REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.user.deleted',
        (event) => {
            console.log(
                '🗑️ USER DELETED REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.user.restored',
        (event) => {
            console.log(
                '♻️ USER RESTORED REAL TIME:',
                event
            );
        }
    )

    .listen(
        '.user.role.changed',
        (event) => {
            console.log(
                '🔑 USER ROLE CHANGED REAL TIME:',
                event
            );
        }
    );

console.log(
    '👂 Listening for Core private events...'
);
