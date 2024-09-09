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

                                    </p><br>
                                    <h1 style="font-size:20px; width:auto; margin-left:auto; margin-right:auto"
                                        class="user-bio"> {{ $profile->bio }}
                                    </h1>
                                    <br>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center" style="padding: 0px;">
                                <div class=" col-md-6 col-8" style="padding: 0px;">
                                    <button id="connectBTN" class="btn btn-block AddBtn rounded-pill px-4 py-3 "
                                        style="background-color: #000000;">
                                        <a style="text-decoration: none;" target="_blank" class="text-white"
                                            href="{{ route('save.contact', $profile->id) }}"><b>Save to contact</b></a>
                                    </button>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    @foreach ($platforms as $chunk)
                                        @foreach ($chunk as $platform)
                                            <div class="col-4 d-flex justify-content-center">
                                                <a class="social" href="javascript:void(0)"
                                                    wire:click="increment('{{ $platform->platform_id }}','{{ $platform->base_url . $platform->path }}')"
                                                    style="{{ $profile->private ? 'filter: blur(5px);' : '' }}">
                                                    <img src="{{ asset(isImageExist($platform->icon, 'platform')) }}"
                                                        class="gallery-image img-fluid"
                                                        style="max-width: 100px; max-height: 100px; object-fit: cover;">
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
        </section>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let url = '{{ $redicretTo }}';
            if (url) {
                location.href = url;
            }
        })

        window.addEventListener('redirect', event => {
            console.log(event.detail.url);
            // window.open(event.detail.url, "_blank")
        });
    </script>

</div>
