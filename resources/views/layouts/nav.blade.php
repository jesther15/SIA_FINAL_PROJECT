<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
    .main-nav {
        display: flex;
        flex-direction: column;

        background-color: var(--primary);
        width: 10vw;
        height: 100vh;
        padding: 2em 0;

    }

    .title {
        color: var(--primary);
        font-size: 2em;
        font-weight: 600;
        text-align: center;
        background-color: white;
        margin-bottom: auto;

        span {
            color: var(--tertiary);
        }
    }

    .Btn {
        font-size: 1.3em;
        background-color: var(--secondary);
        padding: 0.5em 0;
        border: none;
        margin: 0 0.5em;
        margin-bottom: 1em;
        border-radius: 0.5em;
        cursor: pointer;

    }

    .type1 {}
</style>

<div class="modal"
    style="position: fixed; top:50%;left:50%; transform:translate(-50%,-50%); background-color: white;
border-radius: 0.25em; border: 2px solid black; padding:0.5em; display:none; width:max-content">
</div>
<nav class="main-nav">
    <h1 class="title">Jobs.<span>co</span></h1>
    @yield('elements')

    @if (Request::path() == 'dashboard')
        <button class="Btn type1" id="add_resume">Add Resume</button>
    @endif
    <button class="Btn" id="logout">Log out</button>
    <script>
        const resumeBtn = document.querySelector('#add_resume')
        if (resumeBtn) {
            resumeBtn.addEventListener('click', function() {
                window.location.href = '/add_resume';
            });
        }

        document.querySelector('#logout').addEventListener('click', function() {
            document.querySelector('.modal').innerHTML = `
                <div class="logout_card">
                    <div class="card-content">
                        <p class="card-heading">Continue?</p>
                        <p class="card-description">Are you sure you want to log out?</p>
                    </div>
                    <div class="card-button-wrapper">
                        <button class="card-button secondary" id="cancel-logout" >Cancel</button>
                        <button class="card-button primary" id="confirm-logout">Log out</button>
                    </div>
                </div>
            `;
            document.querySelector('.modal').style.display = 'flex';
            document.querySelector('#confirm-logout').addEventListener('click', function() {
                window.location.href = '/logout';
            });

            document.querySelector('#cancel-logout').addEventListener('click', function() {
                document.querySelector('.modal').style.display = 'none';
            });
        });
    </script>

</nav>
