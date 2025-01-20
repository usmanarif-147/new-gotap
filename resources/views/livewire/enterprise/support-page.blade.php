<div>
    <div class="container py-5">
        <!-- Contact Us Section -->
        <div class="mb-5">
            <h2 class="mb-3" style="color: black">How can we help?</h2>
            <!-- Main Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-2" style="color: black">Contact us</h3>
                    <p class="text-muted">Our team of experts is available to provide support through chat or email.</p>

                    <div class="row mt-4">
                        <!-- Left Card -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border shadow-sm">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <!-- Text Content -->
                                    <div>
                                        <h5 class="mb-1" style="color: black">Chat with an Agent</h5>
                                        <p class="text-muted mb-0">Average response time: 25 min</p>
                                    </div>
                                    <!-- Button -->
                                    <a href="https://gotaps.me/support/">
                                        <button class="btn btn-sm btn-outline-dark">Start Chat</button>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Card -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border shadow-sm">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <!-- Text Content -->
                                    <div>
                                        <h5 class="mb-1" style="color: black">Email our Team</h5>
                                        <p class="text-muted mb-0">Average response time: 2 hours</p>
                                    </div>
                                    <!-- Button -->
                                    <a href="https://gotaps.me/support/">
                                        <button class="btn btn-sm btn-outline-dark">Send Email</button>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Articles Section -->
        <div>
            <div class="card p-4 shadow-sm">
                <h5 class="mb-4" style="color: black">Support Articles</h5>
                <div class="row">
                    <!-- Article Card -->
                    @foreach ([['title' => 'GOTaps Teams', 'description' => 'Navigating the GOTaps Teams'], ['title' => 'GOTaps App (iPhone + Android)', 'description' => 'Navigating the GOTaps App'], ['title' => 'GOTaps Accessories & Order', 'description' => 'All things for Products']] as $article)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title" style="color: black">{{ $article['title'] }}</h6>
                                    <p class="card-text">{{ $article['description'] }}</p>
                                    <a href="https://gotaps.me/support/"
                                        class="btn btn-sm btn-outline-dark rounded-pill px-4 py-2 text-center">
                                        View Article <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
