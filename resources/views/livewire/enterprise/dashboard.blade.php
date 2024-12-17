<div>
    <div class="container my-4">
        <!-- Top Section -->
        <div class="row mb-4">
            <!-- Team Performance -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h5 class="section-title">Team Performance | Last 60 days</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div style="text-align: center;">
                            <strong style="display: block;">4</strong>
                            Leads Captured
                        </div>
                        <div
                            style="border-left: 2px solid #000; margin-left: 20px; padding-left: 20px; text-align: center;">
                            <strong style="display: block;">56</strong>
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
                <div class="card p-4 mt-4" style="position: relative; text-align: center;">
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
                    <p class="mt-5">Your campaign goals will appear here</p>
                </div>
            </div>
            <!-- Recent Leads -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <!-- Section Title with Link and Right Aligned Link -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title m-0">
                            Recent Leads Captured
                        </h5>
                        <a href="{{ route('enterprise.leads') }}" class="text-primary text-decoration-none">View more
                            ></a>
                    </div>

                    <!-- List Data -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>test 2</strong></span>
                                    <span>Oct 18</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>Art</strong></span>
                                    <span>Oct 18</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>Roger</strong></span>
                                    <span>Oct 15</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>test 2</strong></span>
                                    <span>Oct 18</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>Art</strong></span>
                                    <span>Oct 18</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Connected with <strong>Roger</strong></span>
                                    <span>Oct 15</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card p-4">
                    <!-- Section Title -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="section-title mb-0">To Do</h5>
                    </div>

                    <!-- To Do List -->
                    <ul class="list-unstyled todo-list mb-0">
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary me-2">TO DO</span>
                                <span>Finish the "Get Started" steps above</span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">Finish Steps</button>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary me-2">TO DO</span>
                                <span>Invite or assign another full team admin</span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">Add Admin</button>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary me-2">TO DO</span>
                                <span>2 unsynced leads</span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">Sync Leads</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h5 class="section-title">Become a Team Expert</h5>
                    <p>Provide maximum value to your team by exploring all the features we have to offer.</p>
                    <button class="btn btn-primary">View Tutorials</button>
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
                        <img src="{{ asset('emailsignature.jpg') }}" height="100" width="150" alt="Feature 1"
                            class="img-fluid shadow" style="border-radius: 8px;">
                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6>Email Signatures</h6>
                        <p>Provide maximum value to your team by discovering all the features that Popi has to offer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center shadow-sm border position-relative"
                    style="border-radius: 10px;">
                    <!-- Centered Image -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('virtual.jpg') }}" height="100" width="150" alt="Feature 1"
                            class="img-fluid shadow" style="border-radius: 8px;">
                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6>Virtual Background</h6>
                        <p>Provide maximum value to your team by discovering all the features that Popi has to offer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card p-4 text-center shadow-sm border position-relative"
                    style="border-radius: 10px;">
                    <!-- Centered Image -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('dashboard.jpg') }}" height="120" width="150" alt="Feature 1"
                            class="img-fluid shadow" style="border-radius: 8px;">
                    </div>
                    <!-- Card Content -->
                    <div>
                        <h6>Email Campaign</h6>
                        <p>Provide maximum value to your team by discovering all the features that Popi has to offer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
