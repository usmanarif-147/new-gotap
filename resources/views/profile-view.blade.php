<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    <title>{{ config('app.name', 'GoTap') }}</title>

    <meta name="description" content="" />

    <!-- Link Stylesheet -->
    {{-- <link rel="stylesheet" href="{{ asset('profile/style.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('profile/newstyle.css') }}" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <!------------------------------------------------ Boxicon CDN ------------------------------------------->

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        .profile_img {
            display: flex;
            justify-content: center;
            border-radius: 50%;
            margin-top: -10%;

        }

        .profile_img img {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            border: 7px solid #fff;
            object-fit: cover;
        }
    </style>
</head>

<body>

    @if (!$redicretTo)
        <section>
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-5 col-12" style="box-shadow: 0 0 15px 5px #ccc">
                        <div class="row d-flex justify-content-center">
                            <a target="_blank" href="https://www.gotaps.me" class="col-12 header-navbar TopBanner">
                                <div class="TopBanner">
                                    Tap here to get your Gotap profile
                                </div>
                            </a>
                            <div class="col-12 d-flex justify-content-center" style="padding: 0px;">
                                <div class="col-12" style="padding: 0px;">
                                    <img style=" width: 100%;" src="{{ asset(isImageExist($profile->cover_photo)) }}">
                                    <div class="profile_img">
                                        <img src="{{ asset(isImageExist($profile->photo, 'profile')) }}"
                                            alt="Profile Photo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center" style="padding: 0px;">
                                <div class="col-md-11" style="padding: 0px;">
                                    <h1 style=" margin-left:30px;" class="user-name">
                                        {{ $profile->name ? $profile->name : $profile->username }}
                                    </h1>
                                    <p style=" margin-left:30px; font-size:16px; color:#24171E;" class="user-name">
                                        @if ($profile->job_title && $profile->company)
                                            {{ $profile->job_title }} at {{ $profile->company }}
                                        @elseif ($profile->job_title)
                                            {{ $profile->job_title }}
                                        @elseif ($profile->company)
                                            {{ $profile->company }}
                                        @endif
                                    </p>
                                    <br>
                                    <h1 style="font-size:20px; width:auto; margin-left:auto; margin-right:auto"
                                        class="user-bio">
                                        {{ $profile->bio }}
                                    </h1>
                                    <br>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center p-0 mt-5">
                                <div class=" col-md-6 col-8" style="padding: 0px;">
                                    <button id="connectBTN" class="btn btn-block AddBtn rounded-pill px-4 py-3 "
                                        style="background-color: #000000;">
                                        <a style="text-decoration: none;" target="_blank" class="text-white"
                                            href="{{ route('save.contact', $profile->id) }}">
                                            <b>Save to contact</b>
                                        </a>
                                    </button>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    @foreach ($platforms as $chunk)
                                        @foreach ($chunk as $platform)
                                            <div class="col-4 d-flex justify-content-center"
                                                style="margin-bottom: 20px">
                                                @if ($profile->private)
                                                    <div class="social text-center"
                                                        style="filter: blur(5px); text-decoration:none; cursor: not-allowed;">
                                                        <img src="{{ asset(isImageExist($platform->icon, 'platform')) }}"
                                                            class="gallery-image img-fluid"
                                                            style="max-width: 90px; max-height: 90px; object-fit: cover; display: block; margin: 0 auto;">
                                                        <label
                                                            style="display: block; font-size:20px; color:black; font-weight:bold">
                                                            {{ $platform->title }}
                                                        </label>
                                                    </div>
                                                @else
                                                    <a class="social text-center" href="javascript:void(0);"
                                                        onclick="incrementPlatform({{ $profile->id }},{{ $platform->platform_id }}, '{{ $platform->base_url . $platform->path }}')"
                                                        style="text-decoration:none;">
                                                        <img src="{{ asset(isImageExist($platform->icon, 'platform')) }}"
                                                            class="gallery-image img-fluid"
                                                            style="max-width: 90px; max-height: 90px; object-fit: cover; display: block; margin: 0 auto;">
                                                        <label
                                                            style="display: block; font-size:20px; color:black; font-weight:bold">
                                                            {{ $platform->title }}
                                                        </label>
                                                    </a>
                                                @endif

                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12  d-flex justify-content-center">

                                <div class=".col-6 .col-md-4" style="padding: 0px;">
                                    <br>
                                    <button id="connectBTN" class="btn btn-block AddBtn rounded-pill px-4 py-3 "
                                        style="background-color: white; color:black; margin-top:5px;">
                                        <a target="_blank" style="text-decoration:none;color:black; " class="text-black"
                                            href="https://www.bit.ly/39sWoAJ"><b>Create your own
                                                profile</b></a>
                                    </button> <br>
                                </div>
                                <!-- butoni tod -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$profilecheck)
                @if ($profile->is_leads_enabled == 1)
                    <!-- Modal -->
                    <div class="modal fade show" id="userDetails" style="display: block;" data-bs-backdrop="static"
                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content position-relative" style="border-radius: 15px;">
                                <!-- Profile Image (Half Outside) -->
                                <div class="profile-image-container position-absolute"
                                    style="top: -50px; left: 50%; transform: translateX(-50%);">
                                    <img src="{{ asset($profile->photo && Storage::disk('public')->exists($profile->photo) ? Storage::url($profile->photo) : 'user.png') }}"
                                        alt="Profile Image" class="rounded-circle"
                                        style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white;">
                                </div>

                                <!-- Modal Header -->
                                <div class="modal-header d-flex flex-column align-items-center"
                                    style="margin-top: 20%">
                                    <h1 class="modal-title fs-5 text-center" id="exampleModalLabel">
                                        Share Your Info with {{ $profile->name ? $profile->name : $profile->username }}
                                    </h1>
                                </div>

                                <div class="modal-body w-75 m-auto">
                                    <!-- Form -->
                                    <form id="viewerForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                required>
                                            <div class="form-text">Please enter your name.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                required>
                                            <div class="form-text">We’ll never share your email with anyone else.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                required>
                                            <div class="form-text">Enter your phone number.</div>
                                        </div>

                                        <input type="text" value="{{ $profile->id }}" name="id"
                                            class="form-control" hidden>
                                        <input type="int" value="{{ $type }}" name="type"
                                            class="form-control" hidden>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer d-flex flex-column align-items-center">
                                    <!-- Save Button -->
                                    <button type="button" class="btn btn-danger w-75 rounded-pill"
                                        onclick="submitForm()" id="saveButton">
                                        Save Detail
                                    </button>

                                    <!-- Loader for save operation -->
                                    <div id="loadingSpinner" class="spinner-border text-light" role="status"
                                        style="display: none;">
                                        <span class="visually-hidden">Saving...</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </section>
    @endif
    <div id="location-reminder"
        style="display: none; position: fixed; bottom: 20px; right: 20px; padding: 10px; background: #ffc; border: 1px solid #cc0;">
        Please enable location access for this page. You may need to update your site settings if prompted.
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let lat = null;
        let long = null;
        $(document).ready(function() {
            let url = '{{ $redicretTo }}';
            if (url) {
                location.href = url;
            }
        });
        // window.addEventListener('closeModal', event => {
        //     $('#userDetails').hide();
        // });
        let locationReminder = document.getElementById('location-reminder');

        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Location granted
                locationReminder.style.display = 'none';
                lat = position.coords.latitude;
                long = position.coords.longitude;
                console.log('Location sent to Livewire:', lat, long);
            },
            function(error) {
                // Location denied
                locationReminder.style.display = 'block';
                console.error("Location permission denied:", error.message);
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
        // });
        function incrementPlatform(id, platformId, url) {
            fetch("{{ route('platform.increment') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        platform_id: platformId,
                        url: url,
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        let link = document.createElement('a');
                        link.href = data.redirect;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script>
        function submitForm() {
            const form = document.getElementById('viewerForm');
            const formData = new FormData(form);

            // Show the loading spinner and disable the button
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('saveButton').disabled = true;

            // Check if geolocation is available
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Attach latitude and longitude to formData
                        formData.append('latitude', position.coords.latitude);
                        formData.append('longitude', position.coords.longitude);

                        // Now send the AJAX request with the location data included
                        sendFormData(formData);
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        // Proceed without location if the user denies access or an error occurs
                        formData.append('latitude', null);
                        formData.append('longitude', null);
                        sendFormData(formData);
                    }
                );
            } else {
                console.error('Geolocation is not supported by this browser.');
                sendFormData(formData); // Send the form without location data
            }
        }

        function sendFormData(formData) {
            fetch("{{ route('viewer.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);

                    // Hide the loading spinner and re-enable the button
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('saveButton').disabled = false;

                    // Optionally reset the form
                    document.getElementById('viewerForm').reset();
                    $('#userDetails').hide();
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Hide the loading spinner and re-enable the button
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('saveButton').disabled = false;
                    $('#userDetails').hide();
                });
        }
    </script>

</body>

</html>
