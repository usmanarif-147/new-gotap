<div>
    <div class="container my-4">
        @php
            $currentHour = now()->format('H');
            if ($currentHour >= 5 && $currentHour < 12) {
                $greeting = 'Good Morning';
            } elseif ($currentHour >= 12 && $currentHour < 17) {
                $greeting = 'Good Afternoon';
            } elseif ($currentHour >= 17 && $currentHour < 21) {
                $greeting = 'Good Evening';
            } else {
                $greeting = 'Good Night';
            }
        @endphp

        <h4 class=" mb-3" style="color: #000">{{ $greeting }}, {{ Auth::user()->name }}!</h4>
        <!-- Top Section -->
        <div class="row mb-4">
            <!-- Team Performance -->
            <div class="col-md-8">
                <div class="card p-4 mb-2">
                    <h5 class="section-title" style="color: #000">Team Performance | Last 60 days</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div style="text-align: center;color: #000">
                            <strong style="display: block;">{{ $leadCaptured }}</strong>
                            Leads Captured
                        </div>
                        <div
                            style="border-left: 2px solid #000; margin-left: 20px; padding-left: 20px; text-align: center;color: #000">
                            <strong style="display: block;">{{ $cardViews }}</strong>
                            Card Views
                        </div>
                        <div
                            style="border-left: 2px solid #000; margin-left: 20px; padding-left: 20px; text-align: center;">
                            <span class="text-primary" style="display: block;"><i
                                    class="tf-icons bx bxs-bar-chart-alt-2"></i></span>
                            <a href="{{ route('enterprise.insights') }}" class="text-primary">View All Insights ></a>
                        </div>
                    </div>
                </div>
                <div class="card p-4 mt-5" style="position: relative; text-align: center;">
                    <!-- Group 3 Image (Small, Centered) -->
                    <img src="{{ asset('Group 3.jpg') }}" alt="Goals Graph"
                        style="width: 150px; position: relative; z-index: 0; margin: 0 auto; display: block;">

                    <!-- Group 1 Image (Above Group 3, Centered) -->
                    <img src="{{ asset('Group 1.jpg') }}"
                        style="
                        width: 300px; /* Larger size */
                        position: absolute; 
                        top: 30%; /* Center vertically */
                        left: 50%; /* Center horizontally */
                        transform: translate(-50%, -50%);
                        z-index: 1;"
                        alt="Goals Graph">

                    <!-- Group 2 Image (Above Group 1, Further Up) -->
                    <img src="{{ asset('Group 2.jpg') }}"
                        style="
                        width: 300px; /* Larger size */
                        position: absolute; 
                        top: 50%; /* Higher position above Group 1 */
                        left: 50%; /* Center horizontally */
                        transform: translate(-50%, -50%);
                        z-index: 2;"
                        alt="Goals Graph">

                    <!-- Caption -->
                    <p class="mt-5" style="color: #000">Your campaign goals will appear here</p>
                </div>
            </div>
            <!-- Recent Leads -->
            <div class="col-md-4">
                <div class="card p-4 h-60 mb-2">
                    <!-- Section Title with Link and Right Aligned Link -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title m-0" style="color: #000">
                            Recent Leads Captured
                        </h5>
                        <a href="{{ route('enterprise.leads') }}" class="text-dark text-decoration-none">View more
                            ></a>
                    </div>

                    <!-- List Data -->
                    <div class="row mt-3">
                        <div class="col-12">
                            @if ($recentLeads->isEmpty())
                                <div class="text-center" style="color: #000">
                                    <p>No Data Found</p>
                                </div>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach ($recentLeads as $leads)
                                        <li class="d-flex justify-content-between mb-3">
                                            <span>Connected with <strong
                                                    style="color: #000">{{ $leads->viewing_name }}</strong></span>
                                            <span>{{ \Carbon\Carbon::parse($leads->created_at)->format('M d') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card p-4">
                    <h5 class="section-title" style="color: #000">Become a Team Expert</h5>
                    <p>Provide maximum value to your team by exploring all the features we have to offer.</p>
                    <button class="btn btn-outline-dark w-50">View Tutorials</button>
                </div>
            </div>

        </div>

        <!-- Bottom Features Section -->
        <div class="row text-center">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center shadow-sm border position-relative"
                    style="border-radius: 10px;">
                    <!-- Centered Image -->
                    <div class="text-center mb-4">
                        <a href="https://gotaps.me/email-signatures" target="_blank">
                            <img src="{{ asset('emailsignature.jpg') }}" alt="Feature 1" class="img-fluid shadow"
                                style="border-radius: 8px;">
                        </a>
                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6 style="color: #000">Email Signatures</h6>
                        <p>Provide maximum value to your team by discovering all the features that Gotaps has to offer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center shadow-sm border position-relative"
                    style="border-radius: 10px;">
                    <!-- Centered Image -->
                    <div class="text-center mb-4">
                        <a href="https://gotaps.me/virtual-background/" target="_blank">
                            <img src="{{ asset('virtual.jpg') }}" alt="Feature 1" class="img-fluid shadow"
                                style="border-radius: 8px;">
                        </a>

                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6 style="color: #000">Virtual Background</h6>
                        <p>Provide maximum value to your team by discovering all the features that Gotaps has to offer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card p-4 text-center shadow-sm border position-relative"
                    style="border-radius: 10px;">
                    <!-- Centered Image -->
                    <div class="text-center mb-4">
                        <a href="https://gotaps.me/email-campaign" target="_blank">
                            <img src="{{ asset('dashboard.jpg') }}" alt="Feature 1" class="img-fluid shadow"
                                style="border-radius: 8px;">
                        </a>

                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6 style="color: #000">Email Campaign</h6>
                        <p>Provide maximum value to your team by discovering all the features that Gotaps has to offer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
