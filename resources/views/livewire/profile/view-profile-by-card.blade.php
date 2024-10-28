<div>
    <style>
        .profile-image-container {
            display: flex;
            justify-content: center;
            /* margin-bottom: 10px; */
            /* z-index: 100; */
        }
    </style>
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
                                                    <a class="social text-center"
                                                        href="{{ $platform->base_url . $platform->path }}"
                                                        target="_blank" style="text-decoration:none;">
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
                    <div class="modal fade {{ $showModal ? 'show' : '' }}" id="userDetails" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content position-relative" style="border-radius: 15px;">
                                <div class="profile-image-container position-absolute"
                                    style="top: -50px; left: 50%; transform: translateX(-50%);">
                                    <img src="{{ asset($profile->photo && Storage::disk('public')->exists($profile->photo) ? Storage::url($profile->photo) : 'user.png') }}"
                                        alt="Profile Image" class="rounded-circle"
                                        style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white;">
                                </div>

                                <!-- Modal Header -->
                                <div class="modal-header d-flex flex-column align-items-center" style="margin-top: 20%">
                                    <h1 class="modal-title fs-5 text-center" id="exampleModalLabel">
                                        Share Your Info with {{ $profile->name ? $profile->name : $profile->username }}
                                    </h1>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body w-75 m-auto">
                                    <!-- Form -->
                                    <form id="userDetailsForm">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" id="name" class="form-control" required>
                                            <div class="form-text">Please enter your name.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input type="email" id="email" class="form-control" required>
                                            <div class="form-text">We’ll never share your email with anyone else.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" id="phone" class="form-control" required>
                                            <div class="form-text">Enter your phone number.</div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <div id="loader" class="spinner-border text-light d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <button type="submit" id="submitButton"
                                        class="btn btn-danger w-75 m-auto rounded-pill" onClick="submitForm()">Save
                                        Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Modal -->



        </section>
    @endif


    <script>
        $(document).ready(function() {

            $('#userDetails').show()

            let url = '{{ $redicretTo }}';
            if (url) {
                location.href = url;
            }
        })

        window.addEventListener('redirect', event => {
            let url = event.detail.url;
            if (url) {
                window.open(event.detail.url, '_blank');
            }
        });

        function submitForm() {
            document.getElementById('loader').classList.remove('d-none');
            document.getElementById('submitButton').disabled = true;
            let name = $('#name').val();
            let email = $('#email').val();
            let phone = $('#phone').val();

            @this.set('name', name);
            @this.set('email', email);
            @this.set('phone', phone);

            setTimeout(function() {
                Livewire.emit('submitForm');
                document.getElementById('loader').classList.add('d-none');
                document.getElementById('submitButton').disabled = false;
            }, 2000);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let locationReminder = document.getElementById('location-reminder');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Location granted
                    Livewire.emit('setLocation', position.coords.latitude, position.coords.longitude);
                },
                function(error) {
                    // Location denied
                    console.error("Location permission denied:", error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        });
    </script>

</div>
