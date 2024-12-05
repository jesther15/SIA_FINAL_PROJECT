<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
        }

        body {
            display: grid;
            grid-template-columns: 1fr 5fr;
            grid-template-rows: 1fr;
            grid-column-gap: 0px;
            grid-row-gap: 0px;

            font-family: "Nunito Sans";
            background-color: lightgrey;
            margin: 0;
        }

        .resume-container {

            overflow: scroll;

            display: flex;
            flex-wrap: wrap;
            gap: 5em;

            padding: 5em 10em;

            height: 100vh;
        }

        .card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background-color: var(--primary);
            width: 35em;
            height: 35em;
            padding: 3em 6em;

            .round {
                border-radius: 100%;
            }
        }

        .applicant-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }

        .buttons {
            display: flex;
            gap: 2em;
        }

        .primary {
            font-size: 1.5em;

            background-color: var(--tertiary);
            padding: 0.3em 2em;

            border: none;

            cursor: pointer;
        }

        .primary.ghost {
            background-color: var(--secondary)
        }
    </style>
</head>

<body>
    @section('user', $userName)
    @include('layouts.nav')

    <div class="resume-container">
        @foreach ($resumes as $resume)
            <div class="card-container">
                <img class="round" src="/images/{{ $resume->image }}" alt="{{ $resume->name }}" height="150"
                    width="150" />
                <div class="applicant-info">
                    <h1>{{ $resume->name }}</h1>
                    <h3>{{ $resume->email }}</h3>
                    <h3>{{ $resume->contact }}</h3>
                </div>
                <div class="buttons">
                    <button class="primary" id="delete" data-id="{{ $resume->id }}">
                        Delete
                    </button>
                    <button class="primary ghost" id="view" data-id="{{ $resume->id }}">
                        View
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @include('layouts.modal')

    <script>
        document.querySelectorAll('.edit_resume').forEach(function(button) {
            button.addEventListener('click', function(e) {
                window.location.href = '/edit_resume/' + button.getAttribute('data-id');
            });
        });

        function setViewDeleteListeners() {
            document.querySelectorAll('#view').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    window.location.href = '/dashboard/' + button.getAttribute('data-id');
                });
            });
            document.querySelectorAll('#delete').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    const modal = document.querySelector('.modal');
                    modal.innerHTML = `
                            <div class="confirmation_card">
                                <div class="card-content">
                                    <p class="card-heading">Delete file?</p>
                                    <p class="card-description">Are you sure you want to delete this resume?</p>
                                </div>
                                <div class="card-button-wrapper">
                                    <button class="card-button secondary" id="no" >Cancel</button>
                                    <button class="card-button primary" id="yes" data-id="${button.getAttribute('data-id')}">Delete</button>
                                </div>
                            </div>
                            `;
                    modal.style.display = 'flex';

                    document.querySelector('#yes').addEventListener('click', async function(e) {

                        await fetch('/dashboard/' + button.getAttribute('data-id'), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(response => {
                            if (response.ok) {
                                window.location.reload();
                            } else {
                                alert('Failed to delete resume.');
                            }
                        });
                    });
                    document.querySelector('#no').addEventListener('click', function(e) {
                        const modal = document.querySelector('.modal');
                        modal.innerHTML = '';
                        modal.style.display = 'none';
                    });
                });
            });
        }
        setViewDeleteListeners();

        document
            .querySelector(".change_theme")
            .addEventListener("click", function() {
                let theme = getCookie("dashboard_theme");
                if (theme === "") {
                    theme = 1;
                } else {
                    theme = theme == 1 ? 0 : 1;
                }
                setCookie("dashboard_theme", theme);
                location.reload();
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

        document.querySelector('#add_resume').addEventListener('click', function() {
            window.location.href = '/add_resume';
        });

        const all_resumes = @json($resumes);

        document.getElementById('sort').addEventListener('change', function(e) {
            const sorted_resumes = all_resumes?.sort((a, b) => {
                switch (e.target.value) {
                    case 'name-asc':
                        return a.name.localeCompare(b.name);
                    case 'name-desc':
                        return b.name.localeCompare(a.name);
                    case 'oldest':
                        return new Date(a.created_at) - new Date(b.created_at);
                    default:
                        return new Date(b.created_at) - new Date(a.created_at);
                }
            });

            const resumes_container = document.querySelector('#all-resume-container');
            resumes_container.innerHTML = '';
            sorted_resumes.forEach(resume => {
                const cardContainer = document.createElement('div');
                cardContainer.classList.add('card-container');
                cardContainer.innerHTML = `
                <img class="round" src="./images/${resume.image}" alt="${resume.name}" height="100" width="100" />
                <div class="applicant-info">
                    <h2>${resume.name}</h2>
                    <h4>${resume.email}</h4>
                    <h4>${resume.contact}</h4>
                </div>
                <div class="skills">
                    <h4>Skills</h4>
                    <ul>
                        ${resume.skills.map(skill => `<li>${skill}</li>`).join('')}
                    </ul>
                </div>
                <div class="buttons">
                    <button class="primary" id="delete" data-id="${resume.id}">Delete</button>
                    <button class="primary ghost" id="view" data-id="${resume.id}">View</button>
                </div>`;
                resumes_container.appendChild(cardContainer);
                setViewDeleteListeners();
            });
        });
    </script>
</body>

</html>
