<div>
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
                                        {{ $profile->job_title }} at {{ $profile->company }}

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
                                                <a class="social text-center"
                                                    href="{{ $platform->base_url . $platform->path }}" target="_blank"
                                                    style="{{ $profile->private ? 'filter: blur(5px);' : '' }}; text-decoration:none">
                                                    <img src="{{ asset(isImageExist($platform->icon, 'platform')) }}"
                                                        class="gallery-image img-fluid"
                                                        style="max-width: 90px; max-height: 90px; object-fit: cover; display: block; margin: 0 auto;">
                                                    <label
                                                        style="display: block; font-size:20px; color:black; font-weight:bold">
                                                        {{ $platform->title }}
                                                    </label>
                                                </a>
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
            @if ($profile->type == 'enterprise')
                <div class="modal fade {{ $showModal ? 'show' : '' }}" id="userDetails" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Details</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>


                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" class="form-control" required>
                                    <div class="form-text">Please Enter your good name !</div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id = "email" class="form-control" required>
                                    <div class="form-text">We'll never share your email with anyone
                                        else.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="phone" id="phone" class="form-control" required>
                                    <div class="form-text">Example Phone "+921234567890"</div>
                                </div>
                                <button type="button" onClick="submitForm()" class="btn btn-primary">Submit</button>

                            </div>
                        </div>
                    </div>
                </div>
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
                window.location.href = url;
            }
        });

        function submitForm() {
            let name = $('#name').val();
            let email = $('#email').val();
            let phone = $('#phone').val();

            @this.set('name', name);
            @this.set('email', email);
            @this.set('phone', phone);

            Livewire.emit('submitForm');
        }
    </script>

</div>
