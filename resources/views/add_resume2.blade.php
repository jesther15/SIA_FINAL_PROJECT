<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Add Resume</title>

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
    </style>
</head>

<body>
    @section('user', $user)
    @section('elements')
        <button type="button" class="Btn discard">Discard</button>
    @endsection
    @include('layouts.nav')
    <form action="/dashboard" method="POST" enctype="multipart/form-data" class="container">
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
                <img src="{{ asset('images/default-avatar.jpg') }}" alt="Profile" id="profileImg" class="profile-img"
                    height="200" width="200">
                <label for="imageInput" class="upload-icon">📷</label>
                <input type="file" id="imageInput" name="_image" accept="image/*" style="display: none">

                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="info-section">
                <h2>Information</h2>

                <label for="email">Email</label>
                <input type="text" name="email" required>
                <label for="birthday">Birthday</label>
                <input id="birthday" name="birthday" type="date" required>
                <label>Age</label>
                <input id="age" type="text" readonly placeholder="Input Birthday First">
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
                <input type="text" name="address" required>
                <label for="contact">Contact</label>
                <input type="text" name="contact" required>
            </div>
        </div>

        <div class="section">

            <section class="objective">
                <h2 class="section-title">Objectives</h2>
                <textarea name="objectives" placeholder="Your Objectives..." required></textarea>
            </section>


            <div class="skill-education">
                <section class="skill-section">
                    <header>
                        <h2 class="section-title">Skills</h2>
                    </header>

                    <div id="additional-skills" class="skills-input">
                        <input type="text" id="skills" name="skills[]" value="{{ old('skills.0') }}">
                        @foreach (old('skills', []) as $index => $skill)
                            @if ($index > 0)
                                <span>
                                    <input type="text" name="skills[]" value="{{ $skill }}">
                                    <button type="button" onclick="removeSkill(this)">Delete</button>
                                </span>
                            @endif
                        @endforeach
                    </div>

                    <button class="skill_btn" type="button" onclick="addSkill()">Add another skill</button>
                </section>

                <section id="educationDisplay" class="education">
                    <h2 class="section-title">Education</h2>

                    <div class="education-level">
                        <h3 class="education-title">College</h3>
                        <div id="college_level" class="education-container">
                            @if (old('college_education'))
                                @foreach (old('college_education', []) as $index => $college)
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

                                        <button type="button" id="remove_college_education">Remove</button>
                                    </div>
                                @endforeach
                            @else
                                <div id="college_education" class="education_content">
                                    <label for="college_name_0">School Name:</label>
                                    <input type="text" id="college_name_0" name="college_education[0][name]"
                                        value="">

                                    <label for="college_course_0">Course/Program:</label>
                                    <input type="text" id="college_course_0" name="college_education[0][course]"
                                        value="">

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
                        <h3 class="education-title">Senior High School</h3>
                        <div id="senior_level" class="education-container">
                            @if (old('senior_education'))
                                @foreach (old('senior_education', []) as $index => $senior)
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

                                        <button type="button" id="remove_senior_education">Remove</button>
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
                                    <input type="text" id="senior_location_0" name="senior_education[0][location]"
                                        value="">

                                    <label for="senior_date_graduated_0">Date</label>
                                    <input type="text" id="senior_date_graduated_0"
                                        name="senior_education[0][date_graduated]" value="">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="education-level">
                        <h3 class="education-title">High School</h3>
                        <div id="highschool_level" class="education-container">
                            @if (old('highschool_education'))
                                @foreach (old('highschool_education', []) as $index => $highSchool)
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

                                        <button type="button" class="remove_highschool_education">Remove</button>
                                    </div>
                                @endforeach
                            @else
                                <div id="highschool_education-item" class="education_content">
                                    <label for="highschool_name_0">School Name:</label>
                                    <input type="text" id="highschool_name_0" name="highschool_education[0][name]"
                                        value="">

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
                        <h3 class="education-title">Elementary</h3>
                        <div id="elementary_level" class="education-container">
                            @if (old('elementary_education'))
                                @foreach (old('elementary_education', []) as $index => $elementary)
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

                                        <button type="button" class="remove_elementary_education">Remove</button>
                                    </div>
                                @endforeach
                            @else
                                <div id="elementary_education-item" class="education_content">
                                    <label for="elementary_name_0">School Name:</label>
                                    <input type="text" id="elementary_name_0" name="elementary_education[0][name]"
                                        value="">

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
            // functions for skills

            // remove extra skills
            function removeSkill(button) {
                button.parentElement.remove();
            }

            // add extra skills
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
</body>

</html>
