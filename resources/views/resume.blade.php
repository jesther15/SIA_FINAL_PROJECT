<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Resume</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: "Nunito sans"
        }

        body {
            display: grid;
            grid-template-columns: 1fr 5fr;
            grid-template-rows: 1fr;
            grid-column-gap: 0px;
            grid-row-gap: 0px;

            margin: 0;
        }

        input,
        textarea {
            font-size: 1.3em;

            background: white;
            padding: 0.25em 0.5em;
            border: 1px solid var(--primary);
            border-radius: 0.5em;
        }

        .container {
            overflow: scroll;
            height: 100vh;
            padding: 2em;
        }

        .section-title {
            color: var(--primary);
        }

        /* personal Info */
        .personal-info {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 1fr;
            grid-column-gap: 5em;
            grid-row-gap: 0px;
        }

        .profile-img-container {
            display: flex;
            flex-direction: column;
        }

        .profile-img {
            border-radius: 100%;
        }

        .upload-icon {
            font-size: 2em;
        }

        .info-section {
            display: flex;
            flex-direction: column;
        }

        .objective {
            width: 100%;

            textarea {
                width: 100%;
            }
        }

        .skill-education {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            grid-template-rows: 1fr;
            grid-column-gap: 15em;
            grid-row-gap: 0px;
        }

        .skills-input {
            display: flex;
            flex-direction: column;
            gap: 2em;

            input {
                width: 100%;
            }

            span {
                position: relative;

                button {
                    position: absolute;
                    top: 50%;
                    left: 105%;
                    transform: translateY(-50%);

                    padding: 0.5em 1em;

                    background-color: var(--secondary);
                    border: none;
                    border-radius: 0.5em;

                    cursor: pointer;
                }
            }
        }

        .skill_btn {
            margin-top: 2em;

            padding: 0.5em 1em;

            background-color: var(--secondary);
            border: none;
            border-radius: 0.5em;

            cursor: pointer;
        }

        .education {}

        .education-container {}

        .education_content {
            display: flex;
            flex-direction: column;
        }

        .education-title {
            color: var(--primary);
        }

        .button-container {

            display: flex;
            justify-content: space-around;

            margin-top: 2em;

            button {

                font-size: 1.5em;

                padding: 0.5em 2em;

                background-color: var(--secondary);
                border: none;
                border-radius: 0.5em;

                cursor: pointer;
            }
        }

        /* no edit */

        .edit_add {
            display: flex;
            justify-content: center;
            gap: 2em;

            margin-top: 2em;

            button {
                font-size: 1.5em;
            }
        }

        .applications {
            display: flex;
            flex-direction: column;

            main {
                display: flex;
                flex-wrap: wrap;
                gap: 2em;

                .application {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 1em;
                    color: white;

                    background-color: var(--primary);
                    padding: 0.5em 2em;

                    border-radius: 1em;
                }
            }
        }

        .application-bg {
            position: fixed;
            display: none;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100vw;
            height: 100vh;
        }

        .application-form {
            color: white;
            background-color: var(--primary);
            padding: 2em 4em;

            border-radius: 2em;
        }
    </style>
</head>

<body>
    @section('user', $user)
    @section('elements')
        <a href="/dashboard" class="Btn" style="text-align: center;">
            <button style="background-color:transparent; border:none; font-size: 1em">Back</button>
        </a>
    @endsection
    @include('layouts.nav')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (request()->has('edit') && request()->get('edit') == 'true')
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/dashboard/{{ $id }}" method="POST" enctype="multipart/form-data" class="container">
            @csrf
            <div class="personal-info">
                <div class="profile-img-container">
                    <img src="{{ env('LOCAL') ? '' : '/public' }}/images/{{ $image }}" alt="Profile"
                        id="profileImg" class="profile-img" height="200" width="200">
                    <label for="imageInput" class="upload-icon">📷</label>
                    <input type="file" id="imageInput" name="_image" accept="image/*" style="display: none">

                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ $name }}" required>
                </div>

                <div class="info-section">
                    <h2>Information</h2>

                    <label for="email">Email</label>
                    <input type="text" name="email" value="{{ $email }}" required>
                    <label for="birthday">Birthday</label>
                    <input id="birthday" name="birthday" type="date" value="{{ $birthday }}" required>
                    <label>Age</label>
                    <input id="age" type="text" readonly placeholder="Input Birthday First"
                        value="{{ \Carbon\Carbon::parse($birthday)->age }}">
                    <script>
                        document.querySelector('#birthday').addEventListener('change', function(e) {
                            const birthday = new Date(e.target.value);
                            const today = new Date();
                            const age = today.getFullYear() - birthday.getFullYear();
                            if (today.getMonth() < birthday.getMonth() || (today.getMonth() == birthday.getMonth() && today
                                    .getDate() < birthday.getDate())) {
                                age--;
                            }
                            document.querySelector('#age').value = age;
                        })
                    </script>
                    <label for="address">Address</label>
                    <input type="text" name="address" value="{{ $address }}" required>
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" value="{{ $contact }}" required>
                </div>
            </div>

            <div class="section">

                <section class="objective">
                    <h2 class="section-title">Objectives</h2>
                    <textarea name="objectives" placeholder="Your Objectives..." required>{{ $objectives }}</textarea>
                </section>


                <div class="skill-education">
                    <section class="skill-section">
                        <header>
                            <h2 class="section-title">Skills</h2>
                        </header>

                        <div id="additional-skills" class="skills-input">
                            @foreach ($skills as $skill)
                                <span>
                                    <input type="text" name="skills[]" value="{{ $skill }}">
                                    <button type="button" onclick="removeSkill(this)">Delete</button>
                                </span>
                            @endforeach
                        </div>

                        <button class="skill_btn" type="button" onclick="addSkill()">Add another skill</button>
                    </section>

                    <section id="educationDisplay" class="education">
                        <h2 class="section-title">Education</h2>

                        <div class="education-level">
                            <h3>College</h3>
                            <div id="college_level" class="education-container">
                                @if (!empty($education['college']))
                                    @foreach ($education['college'] as $index => $college)
                                        <div id="college_education" class="education_content">
                                            <label for="college_name_{{ $index }}">School Name:</label>
                                            <input type="text" id="college_name_{{ $index }}"
                                                name="college_education[{{ $index }}][name]"
                                                value="{{ $college['name'] }}">

                                            <label for="college_course_{{ $index }}">Course/Program:</label>
                                            <input type="text" id="college_course_{{ $index }}"
                                                name="college_education[{{ $index }}][course]"
                                                value="{{ $college['course'] }}">

                                            <label for="college_location_{{ $index }}">Location:</label>
                                            <input type="text" id="college_location_{{ $index }}"
                                                name="college_education[{{ $index }}][location]"
                                                value="{{ $college['location'] }}">

                                            <label for="college_date_graduated_{{ $index }}">Date</label>
                                            <input type="text" id="college_date_graduated_{{ $index }}"
                                                name="college_education[{{ $index }}][date_graduated]"
                                                value="{{ $college['date_graduated'] }}">


                                        </div>
                                    @endforeach
                                @else
                                    <div id="college_education" class="education_content">
                                        <label for="college_name_0">School Name:</label>
                                        <input type="text" id="college_name_0" name="college_education[0][name]"
                                            value="">

                                        <label for="college_course_0">Course/Program:</label>
                                        <input type="text" id="college_course_0"
                                            name="college_education[0][course]" value="">

                                        <label for="college_location_0">Location:</label>
                                        <input type="text" id="college_location_0"
                                            name="college_education[0][location]" value="">

                                        <label for="college_date_graduated_0">Date</label>
                                        <input type="text" id="college_date_graduated_0"
                                            name="college_education[0][date_graduated]" value="">
                                    </div>
                                @endif
                            </div>


                        </div>

                        <div class="education-level">
                            <h3>Senior High School</h3>
                            <div id="senior_level" class="education-container">
                                @if (!empty($education['senior']))
                                    @foreach ($education['senior'] as $index => $senior)
                                        <div id="senior_education-item" class="education_content">
                                            <label for="senior_name_{{ $index }}">School Name:</label>
                                            <input type="text" id="senior_name_{{ $index }}"
                                                name="senior_education[{{ $index }}][name]"
                                                value="{{ $senior['name'] }}">

                                            <label for="senior_course_{{ $index }}">Course/Program:</label>
                                            <input type="text" id="senior_course_{{ $index }}"
                                                name="senior_education[{{ $index }}][course]"
                                                value="{{ $senior['course'] }}">

                                            <label for="senior_location_{{ $index }}">Location:</label>
                                            <input type="text" id="senior_location_{{ $index }}"
                                                name="senior_education[{{ $index }}][location]"
                                                value="{{ $senior['location'] }}">

                                            <label for="senior_date_graduated_{{ $index }}">Date</label>
                                            <input type="text" id="senior_date_graduated_{{ $index }}"
                                                name="senior_education[{{ $index }}][date_graduated]"
                                                value="{{ $senior['date_graduated'] }}">


                                        </div>
                                    @endforeach
                                @else
                                    <div id="senior_education-item" class="education_content">
                                        <label for="senior_name_0">School Name:</label>
                                        <input type="text" id="senior_name_0" name="senior_education[0][name]"
                                            value="">

                                        <label for="senior_course_0">Course/Program:</label>
                                        <input type="text" id="senior_course_0" name="senior_education[0][course]"
                                            value="">

                                        <label for="senior_location_0">Location:</label>
                                        <input type="text" id="senior_location_0"
                                            name="senior_education[0][location]" value="">

                                        <label for="senior_date_graduated_0">Date</label>
                                        <input type="text" id="senior_date_graduated_0"
                                            name="senior_education[0][date_graduated]" value="">
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="education-level">
                            <h3>High School</h3>
                            <div id="highschool_level" class="education-container">
                                @if (!empty($education['highschool']))
                                    @foreach ($education['highschool'] as $index => $highSchool)
                                        <div id="highschool_education-item" class="education_content">
                                            <label for="highschool_name_{{ $index }}">School Name:</label>
                                            <input type="text" id="highschool_name_{{ $index }}"
                                                name="highschool_education[{{ $index }}][name]"
                                                value="{{ $highSchool['name'] }}">

                                            <label for="highschool_location_{{ $index }}">Location:</label>
                                            <input type="text" id="highschool_location_{{ $index }}"
                                                name="highschool_education[{{ $index }}][location]"
                                                value="{{ $highSchool['location'] }}">

                                            <label for="highschool_date_graduated_{{ $index }}">Date</label>
                                            <input type="text" id="highschool_date_graduated_{{ $index }}"
                                                name="highschool_education[{{ $index }}][date_graduated]"
                                                value="{{ $highSchool['date_graduated'] }}">


                                        </div>
                                    @endforeach
                                @else
                                    <div id="highschool_education-item" class="education_content">
                                        <label for="highschool_name_0">School Name:</label>
                                        <input type="text" id="highschool_name_0"
                                            name="highschool_education[0][name]" value="">

                                        <label for="highschool_location_0">Location:</label>
                                        <input type="text" id="highschool_location_0"
                                            name="highschool_education[0][location]" value="">

                                        <label for="highschool_date_graduated_0">Date</label>
                                        <input type="text" id="highschool_date_graduated_0"
                                            name="highschool_education[0][date_graduated]" value="">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="education-level">
                            <h3>Elementary</h3>
                            <div id="elementary_level" class="education-container">
                                @if (!empty($education['elementary']))
                                    @foreach ($education['elementary'] as $index => $elementary)
                                        <div id="elementary_education-item" class="education_content">
                                            <label for="elementary_name_{{ $index }}">School Name:</label>
                                            <input type="text" id="elementary_name_{{ $index }}"
                                                name="elementary_education[{{ $index }}][name]"
                                                value="{{ $elementary['name'] }}">

                                            <label for="elementary_location_{{ $index }}">Location:</label>
                                            <input type="text" id="elementary_location_{{ $index }}"
                                                name="elementary_education[{{ $index }}][location]"
                                                value="{{ $elementary['location'] }}">

                                            <label for="elementary_date_graduated_{{ $index }}">Date</label>
                                            <input type="text" id="elementary_date_graduated_{{ $index }}"
                                                name="elementary_education[{{ $index }}][date_graduated]"
                                                value="{{ $elementary['date_graduated'] }}">

                                        </div>
                                    @endforeach
                                @else
                                    <div id="elementary_education-item" class="education_content">
                                        <label for="elementary_name_0">School Name:</label>
                                        <input type="text" id="elementary_name_0"
                                            name="elementary_education[0][name]" value="">

                                        <label for="elementary_location_0">Location:</label>
                                        <input type="text" id="elementary_location_0"
                                            name="elementary_education[0][location]" value="">

                                        <label for="elementary_date_graduated_0">Date</label>
                                        <input type="text" id="elementary_date_graduated_0"
                                            name="elementary_education[0][date_graduated]" value="">

                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div id="modal"
                style="position: fixed; top:50%;left:50%; transform:translate(-50%,-50%); background-color: white;
                border-radius: 0.25em; border: 2px solid black; padding:0.5em; display:none;">
                <div style="background:white; padding:2rem; border-radius:8px; text-align:center;">
                    <p>Are you sure you want to discard changes?</p>
                    <button type="button" onclick="confirmDiscard()">Yes</button>
                    <button type="button" onclick="closeModal()">No</button>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="action-buttons">Submit</button>
                <button type="button" class="discard" class="action-buttons">Discard</button>
            </div>

            <script>
                function confirmDiscard() {
                    window.location.href = '/dashboard';
                }

                function closeModal() {
                    document.getElementById('modal').style.display = 'none';
                }

                document.querySelectorAll('.discard').forEach(function(button) {
                    button.addEventListener('click', function() {
                        document.getElementById('modal').style.display = 'flex';
                    });
                });
            </script>
            </div>
            <script>
                document.getElementById('imageInput').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('profileImg').src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            </script>
            <script>
                function removeSkill(button) {
                    button.parentElement.remove();
                }

                function addSkill() {
                    const newSkillContainer = document.createElement('span');
                    const newSkill = document.createElement('input');
                    newSkill.type = 'text';
                    newSkill.name = 'skills[]';
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.textContent = 'Delete';
                    deleteButton.onclick = function() {
                        removeSkill(deleteButton);
                    };
                    newSkillContainer.appendChild(newSkill);
                    newSkillContainer.appendChild(deleteButton);
                    document.getElementById('additional-skills').appendChild(newSkillContainer);
                }

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
        </form>
    @else
        {{-- ///////////////////////////////////////////////// No Edits ////////////////////////////////////// --}}
        <div class="resume_content">
            <nav class="edit_add">
                <button id="btn-edit"
                    onclick="if(confirm('Are you sure you want to edit?')) { window.location.href = window.location.pathname + '?edit=true'; }">Edit
                </button>
                <button id="add-application">Add Application</button>
            </nav>

            <div class="applications">
                <h2>Applications</h2>
                <main>

                    @if (!empty($applications))
                        @foreach ($applications as $application)
                            <div class="application">
                                <img src="{{ $application['company_image'] }}"
                                    alt="{{ $application['company_name'] }}" height="50">
                                <p>{{ $application['company_name'] }}</p>
                                <p>{{ $application['status'] }}</p>
                                <p>{{ $application['date'] ?? '' }}</p>
                                <form action="/delete_application" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="index" value="{{ $loop->index }}">
                                    <input type="hidden" name="resume_id" value="{{ $id }}">
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this application?')">❌</button>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </main>
            </div>
            <div class="container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @csrf
                <div class="personal-info">
                    <div class="profile-img-container">
                        <img src="{{ env('LOCAL') ? '' : '/public' }}/images/{{ $image }}" alt="Profile"
                            id="profileImg" class="profile-img" height="150" width="150">
                        <p><strong>Full Name:</strong> {{ $name }}</p>
                    </div>

                    <div class="info-section">
                        <h2 class="section-title">Information</h2>

                        <p><strong>Email:</strong> {{ $email }}</p>
                        <p><strong>Birthday:</strong> {{ $birthday }}</p>
                        <p><strong>Age:</strong> <span id="age"></span></p>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const birthday = new Date("{{ $birthday }}");
                                const today = new Date();
                                let age = today.getFullYear() - birthday.getFullYear();
                                const monthDiff = today.getMonth() - birthday.getMonth();
                                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                                    age--;
                                }
                                document.getElementById('age').textContent = age;
                            });
                        </script>
                        <p><strong>Address:</strong> {{ $address }}</p>
                        <p><strong>Contact:</strong> {{ $contact }}</p>
                    </div>

                </div>
                <div class="section">

                    <section class="objective">
                        <h2 class="section-title">Objectives</h2>
                        <p>{{ $objectives }}</p>
                    </section>

                    <div class="skill-education">

                        <section class="skill-section">
                            <header>
                                <h2>Skills</h2>
                            </header>
                            <div id="additional-skills" class="skills-input">
                                @foreach ($skills as $skill)
                                    <span>
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </section>


                        <section id="educationDisplay" class="education">
                            <h2 class="section-title">Education</h2>

                            @if (!empty($education['college']))
                                <div class="education-level">
                                    <h3>College</h3>
                                    <div class="education-container">
                                        @foreach ($education['college'] as $index => $college)
                                            <div id="college_education" class="education_content"
                                                style="padding-left: 1em;">
                                                <p><strong>School Name:</strong> {{ $college['name'] }}</p>
                                                <p><strong>Location:</strong> {{ $college['location'] }}</p>
                                                <p><strong>Course/Program:</strong> {{ $college['course'] }}</p>
                                                <p><strong>Date:</strong> {{ $college['date_graduated'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($education['senior']))
                                <div class="education-level">
                                    <h3>Senior High School</h3>
                                    <div class="education-container">
                                        @foreach ($education['senior'] as $index => $senior)
                                            <div class="education_content" style="padding-left: 1em;">
                                                <p><strong>School Name:</strong> {{ $senior['name'] }}</p>
                                                <p><strong>Location:</strong> {{ $senior['location'] }}</p>
                                                <p><strong>Course/Program:</strong> {{ $senior['course'] }}</p>
                                                <p><strong>Date:</strong> {{ $senior['date_graduated'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($education['highschool']))
                                <div class="education-level">
                                    <h3>Junior Highschool</h3>
                                    <div class="education-container">
                                        @foreach ($education['highschool'] as $index => $highschool)
                                            <div class="education_content" style="padding-left: 1em;">
                                                <p><strong>School Name:</strong> {{ $highschool['name'] }}</p>
                                                <p><strong>Location:</strong> {{ $highschool['location'] }}</p>
                                                <p><strong>Date:</strong> {{ $highschool['date_graduated'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($education['elementary']))
                                <div class="education-level">
                                    <h3>Elementary</h3>
                                    <div class="education-container">
                                        @foreach ($education['elementary'] as $index => $elementary)
                                            <div class="education_content" style="padding-left: 1em;">
                                                <p><strong>School Name:</strong> {{ $elementary['name'] }}</p>
                                                <p><strong>Location:</strong> {{ $elementary['location'] }}</p>
                                                <p><strong>Date:</strong> {{ $elementary['date_graduated'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
                <div id="modal"
                    style="position: fixed; top:50%;left:50%; transform:translate(-50%,-50%); background-color: black;
            border-radius: 0.25em; border: 2px solid black; padding:0.5em; display:none;">
                    <div style="background:white; padding:2rem; border-radius:8px; text-align:center;">
                        <p>Are you sure you want to discard changes?</p>
                        <button type="button" onclick="confirmDiscard()">Yes</button>
                        <button type="button" onclick="closeModal()">No</button>
                    </div>
                </div>
            </div>
            <script>
                function confirmDiscard() {
                    window.location.href = '/dashboard';
                }

                function closeModal() {
                    document.getElementById('modal').style.display = 'none';
                }

                document.querySelectorAll('.discard').forEach(function(button) {
                    button.addEventListener('click', function() {
                        document.getElementById('modal').style.display = 'flex';
                    });
                });
            </script>
        </div>

        <script>
            function removeSkill(button) {
                button.parentElement.remove();
            }

            function addSkill() {
                const newSkillContainer = document.createElement('span');
                const newSkill = document.createElement('input');
                newSkill.type = 'text';
                newSkill.name = 'skills[]';
                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.textContent = 'Delete';
                deleteButton.onclick = function() {
                    removeSkill(deleteButton);
                };
                newSkillContainer.appendChild(newSkill);
                newSkillContainer.appendChild(deleteButton);
                document.getElementById('additional-skills').appendChild(newSkillContainer);
            }

            document
                .querySelector(".change_theme")
                .addEventListener("click", function() {
                    let theme = getCookie("resume_theme");
                    if (theme === "") {
                        theme = 1;
                    } else {
                        theme = theme == 1 ? 0 : 1;
                    }
                    setCookie("resume_theme", theme);
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
        </script>

        </div>
        <div class="application-bg">
            <div class="application-form">
                <h1>Add Application</h1>
                <form action="/add_application" method="POST">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <input type="hidden" name="resume_id" value="{{ $id }}">
                    <select name="company" id="company">
                        <option value="">Select</option>
                        <option value="Jobstreet"
                            data-image="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyFaYhPIvmCaPQMMabk0mlaHyAGnofey1JAQ&s">
                            Jobstreet</option>
                        <option value="LinkedIn"
                            data-image="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRokEYt0yyh6uNDKL8uksVLlhZ35laKNQgZ9g&s">
                            LinkedIn</option>
                        <option value="other">Other</option>
                    </select>

                    <label for="company_name">Name:</label>
                    <input type="text" id="company_name" name="company_name" value="">
                    <div>
                        <label for="company_image">Image URL:</label>
                        <input type="text" id="company_image" name="company_image"
                            oninput="previewApplicationImageUrl(event)">
                        <img id="application_image_preview" src="#" alt="Application Image Preview"
                            style="display:none;" height="50">
                    </div>
                    <script>
                        function previewApplicationImageUrl(event) {
                            const url = event.target.value;
                            const output = document.getElementById('application_image_preview');
                            if (url) {
                                output.src = url;
                                output.style.display = 'block';
                            } else {
                                output.style.display = 'none';
                            }
                        }
                    </script>
                    <script>
                        const companySelect = document.getElementById('company');
                        const nameInput = document.getElementById('company_name');
                        const imageInput = document.getElementById('company_image');
                        const imagePreview = document.getElementById('application_image_preview');

                        companySelect.addEventListener('change', function() {
                            const selectedOption = companySelect.options[companySelect.selectedIndex];
                            if (selectedOption.value === 'other' || !selectedOption.value) {
                                nameInput.value = '';
                                imageInput.value = '';
                                imagePreview.style.display = 'none';
                            } else {
                                nameInput.value = selectedOption.value;
                                imageInput.value = selectedOption.getAttribute('data-image');
                                imagePreview.src = selectedOption.getAttribute('data-image');
                                imagePreview.style.display = 'block';
                            }
                        });
                    </script>


                    <label for="status">Status</label>
                    <select id="status" name="status" onchange="toggleCustomStatus(this)">
                        <option value="hired">Hired</option>
                        <option value="interview">Interview</option>
                        <option value="applied">Applied</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="text" id="custom_status" name="custom_status" style="display:none;"
                        placeholder="Enter custom status">

                    <script>
                        function toggleCustomStatus(select) {
                            var customStatusInput = document.getElementById('custom_status');
                            if (select.value === 'other') {
                                customStatusInput.style.display = 'block';
                            } else {
                                customStatusInput.style.display = 'none';
                            }
                        }
                    </script>

                    <label for="date">Date</label>
                    <input type="text" id="date" name="date" value="{{ date('Y-m-d') }}">

                    <div class="button-container">
                        <button type="button" class="close">Close</button>
                        <button id="submitButton">
                            <span class="txt">Submit</span>
                            <span class="loader-container">
                                <span class="loader"></span>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        @include('layouts.modal')
        <script>
            document.querySelector('#add-application').addEventListener('click', function() {
                document.querySelector('.application-bg').style.display = 'flex';

                document.querySelector('.application-form .close').addEventListener('click', function() {
                    document.querySelector('.application-bg').style.display = 'none';
                });
            });
        </script>
    @endif
</body>

</html>
