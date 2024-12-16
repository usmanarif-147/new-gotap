<div>
    <div class="row mb-5">

        <x-custom.dashboard-card background="bg-label-info" icon="bx bx-user-pin" title="Profiles" :total="$totalProfiles" />
        <x-custom.dashboard-card background="bg-label-primary" icon="bx bx-credit-card-alt" title="Active Cards"
            :total="$activeCards" />
        <x-custom.dashboard-card background="bg-label-warning" icon="bx bxs-group" title="Total Leads" :total="$leads" />

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
    @endsection
</div>
