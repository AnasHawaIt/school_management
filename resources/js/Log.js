import axios from 'axios';

console.log('🔥 LOGIN FILE LOADED');

const form = document.getElementById('login-form');

if (form) {
    console.log('✅ Login form found');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const email = document.getElementById('email')?.value;
        const password = document.getElementById('password')?.value;

        console.log('🔐 Attempting API login:', email);

        // Remove any old token before attempting a new login
        localStorage.removeItem('auth_token');

        try {
            const response = await axios.post('/api/login', {
                email,
                password,
            });

            console.log('✅ Login API response:', response.data);

            const token = response.data?.data?.token;

            if (!token) {
                console.error('❌ Token missing from login response');
                console.error('❌ Full response:', response.data);

                alert('Login succeeded, but token was not returned.');

                return;
            }

            localStorage.setItem('auth_token', token);

            console.log('✅ Login successful');
            console.log('✅ Token saved to localStorage');

            console.log(
                '🔐 Token exists:',
                localStorage.getItem('auth_token')
                    ? 'YES'
                    : 'NO'
            );

            // Reload the page so Echo starts with the new token
            window.location.reload();

        } catch (error) {
            console.error('❌ Login failed:', error.response?.data);

            alert(
                error.response?.data?.message ??
                'Login failed'
            );
        }
    });

} else {
    console.log('ℹ️ Login form not found on this page');
}
