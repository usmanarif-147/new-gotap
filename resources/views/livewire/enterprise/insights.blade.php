<div>
    <div class="row mb-5">

        <x-custom.enterprise-dashboard-card background="bg-label-info" icon="bx bx-user-pin" title="Profiles"
            :total="$totalProfiles" />
        <x-custom.enterprise-dashboard-card background="bg-label-primary" icon="bx bx-credit-card-alt" title="Active Cards"
            :total="$activeCards" />
        <x-custom.enterprise-dashboard-card background="bg-label-warning" icon="bx bxs-group" title="Total Leads"
            :total="$leads" />
        <x-custom.enterprise-dashboard-card background="bg-label-warning" icon="bx bxs-group" title="Total Subteams"
            :total="$subteamsCount" />
        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Top Performers</h5>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="btn-group">
                            <button wire:click="switchToViews"
                                class="btn {{ $isActive === 'views' ? 'btn-dark' : 'btn-outline-dark' }}">
                                Views
                            </button>

                            <button wire:click="switchToLeads"
                                class="btn {{ $isActive === 'leads' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Leads
                            </button>
                        </div>
                    </div>
                    <canvas id="profilePlatformChart" style="width:100%;max-width:600;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-4">
            <div class="card" style="height: 100%">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Views Over Time</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Views</th>
                                <th scope="col">Taps</th>
                                <th scope="col">Leads</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($profiles->isNotEmpty())
                                @foreach ($profiles as $profile)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                                class="rounded-circle me-2" alt="Avatar" width="32"
                                                height="32">
                                            {{ $profile->username }}
                                        </td>
                                        <td>{{ $profile->tiks }}</td>
                                        <td>{{ $profile->taps }}</td>
                                        <td>{{ $profile->leads_count }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="mb-0">No Data Found</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Leads Over Time</h6>
                </div>
                <div class="card-body p-0">
                    <canvas id="leadsChart" width="400" height="160"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-4">
            <div class="card" style="height: 100%">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Sub Teams</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($subteams->isNotEmpty())
                                @foreach ($subteams as $ind => $subteam)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($subteam->logo && Storage::disk('public')->exists($subteam->logo) ? Storage::url($subteam->logo) : 'user.png') }}"
                                                class="rounded-circle me-2" alt="Avatar" width="32"
                                                height="32">
                                            {{ $subteam->name ? $subteam->name : 'N/A' }}
                                        </td>
                                        <td>
                                            <span>
                                                <i class="bx bx-user"></i>
                                                <span>{{ $subteam->profile_count }}</span>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="mb-0">No Data Found</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-4">
            <div class="card" style="height: 100%">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Email Compaigns</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Nr</th>
                                <th scope="col">Compaign Name</th>
                                <th scope="col">Send</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($compaigns->isNotEmpty())
                                @foreach ($compaigns as $ind => $compaign)
                                    <tr>
                                        <td>
                                            {{ $ind + 1 }}
                                        </td>
                                        <td>
                                            {{ $compaign->subject }}
                                        </td>
                                        <td>
                                            {{ $compaign->total }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="mb-0">No Data Found</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>


    </div>

    @section('script')
        <script src="{{ asset('assets/js/chart.js') }}"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                const canvas = document.getElementById('profilePlatformChart');
                const ctx = canvas.getContext('2d');
                const chartData = @json($viewsData);


                // Custom plugin to draw images next to labels
                const imagePlugin = {
                    id: 'imagePlugin',
                    afterDatasetsDraw: function(chart) {
                        const ctx = chart.ctx;
                        const meta = chart.getDatasetMeta(0);

                        const imgSize = 30;
                        if (chartData.photos && chartData.photos.length > 0) {
                            chartData.photos.forEach(function(photoUrl, index) {
                                const dataPoint = meta.data[index];
                                if (dataPoint) {
                                    const img = new Image();
                                    img.src = photoUrl;
                                    img.onload = function() {
                                        const position = dataPoint
                                            .tooltipPosition();
                                        const x = position.x - (imgSize - 5);
                                        const y = chart.chartArea.bottom - 20;

                                        // Create a circular clipping path
                                        ctx.save();
                                        ctx.beginPath();
                                        ctx.arc(x + imgSize / 2, y + imgSize / 2, imgSize / 2, 0,
                                            Math
                                            .PI *
                                            2);
                                        ctx.closePath();
                                        ctx.clip(); // Clip to the circular area

                                        // Draw the image
                                        ctx.drawImage(img, x, y, imgSize, imgSize);
                                        ctx.restore(); // Restore the context
                                    };
                                }
                            });
                        }
                    }
                };

                // Create the chart
                let profilePlatformChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Total Views',
                            data: chartData.data,
                            borderRadius: 15,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false // Ensure all labels are displayed
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    },
                    plugins: [imagePlugin] // Add the custom image plugin
                });
                window.addEventListener('update-chart', event => {
                    if (event.detail.isActive === 'views') {
                        profilePlatformChart.data.datasets[0].label = 'Total Views';
                    } else {
                        profilePlatformChart.data.datasets[0].label = 'Total Leads';
                    }
                    profilePlatformChart.data.labels = event.detail.data.labels;
                    profilePlatformChart.data.datasets[0].data = event.detail.data.data;
                    profilePlatformChart.update();
                });
            });
        </script>
        <script>
            const graphData = @json($leadsGraphData);
            const labels = graphData.map(data => data.date);
            const dataValues = graphData.map(data => data.leads_count);

            const ctx = document.getElementById('leadsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Leads Count',
                        data: dataValues,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Leads Count'
                            },
                            grid: {
                                display: false
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endsection
</div>
