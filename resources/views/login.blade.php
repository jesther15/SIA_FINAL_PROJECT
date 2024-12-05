<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Anta&family=Audiowide&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Russo+One&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            font-family: "Nunito Sans"
        }

        body {
            padding: 0;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("{{ asset('icons/logInBackground.png') }}");
            width: 100vw;
            height: 100vh;
        }

        .login-content {
            background-color: rgba(255, 255, 255, 0.9);
            width: 20%;
            height: 50%;
            border-radius: 0.5em;

            form {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-around;
                height: 100%;

                .title {
                    color: var(--primary);
                    font-size: 3em;
                    font-weight: 400;

                    span {
                        color: var(--tertiary)
                    }
                }

                .div {
                    width: 70%;

                    input {
                        font-size: 1.2em;

                        width: 100%;
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid black;
                    }
                }

                .btn {
                    font-size: 1.3em;
                    background-color: var(--secondary);
                    padding: 0.2em 3.5em;

                    border: 1px solid black;
                    border-radius: 0.2em;

                    cursor: pointer;
                }
            }
        }
    </style>

</head>


<body>
    <div class="container">
        <div class="img">
            <div class="profile-background-circle"></div>
        </div>
        <div class="login-content">
            <form action="/login" method="POST">
                @if ($errors->any())
                    <div class="errors">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @csrf
                <h2 class="title">Jobs.<span>co</span></h2>
                <h4 class="welcome">Welcome! Please log in to continue</h4>

                <div class="div">
                    <input placeholder="email" name="email" type="email" class="input" required
                        value="{{ old('email') }}">
                    <h3>Email</h3>

                </div>

                <div class="div">
                    <input placeholder="password" name="password" type="password" class="input" required>
                    <h3>Password</h3>
                </div>

                <input type="submit" class="btn" value="Log In">
            </form>
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll(".input");
        const viewPass = document.querySelector(".icon");
        const passStatus = document.querySelector('input[name="password"]');


        function addcl() {
            let parent = this.parentNode.parentNode;
            parent.classList.add("focus");
        }

        function remcl() {
            let parent = this.parentNode.parentNode;
            if (this.value == "") {
                parent.classList.remove("focus");
            }
        }

        inputs.forEach(input => {
            input.addEventListener("focus", addcl);
            input.addEventListener("blur", remcl);
        });

        function setCookie(name, value) {
            const expires = new Date();
            expires.setTime(expires.getTime() + 30 * 24 * 60 * 60 * 1000);
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(";").shift();
            return "";
        }
    </script>
</body>

</html>
