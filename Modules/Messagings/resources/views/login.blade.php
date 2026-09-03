<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Login</title>
        </head>
        <body>

            <h1>Login</h1>

            <form id="login-form">

                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        id="email"
                        required
                        >
                </div>

                <br>

                <div>
                    <label>Password</label>
                    <input
                        type="password"
                        id="password"
                        required
                        >
                </div>

                <br>

                <button type="submit">
            Login
                </button>

            </form>

        @vite(['resources/js/login.js'])

        </body>
    </html>
