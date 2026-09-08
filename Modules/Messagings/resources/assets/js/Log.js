import axios from 'axios';

const form = document.getElementById('login-form');

if (form) {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post('/api/login', {
                email,
                password,
            });

            const token = response.data.data.token;

            localStorage.setItem('auth_token', token);

            console.log('✅ Login successful');
            console.log('✅ Token saved');

            // إعادة تحميل الصفحة حتى يتم إنشاء Echo
            // بعد وجود الـ token
            window.location.reload();

        } catch (error) {
            console.error('❌ Login failed:', error.response?.data);

            alert(
                error.response?.data?.message ??
                'Login failed'
            );
        }
    });
}
