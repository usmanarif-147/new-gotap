<div>
    <div class="row mb-5">

        <x-custom.dashboard-card background="bg-label-info" icon="bx bx-user-pin" title="Profiles" :total="$totalProfiles" />
        <x-custom.dashboard-card background="bg-label-primary" icon="bx bx-credit-card-alt" title="Active Cards"
            :total="$activeCards" />
        <x-custom.dashboard-card background="bg-label-warning" icon="bx bxs-group" title="Total Leads" :total="$leads" />

        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <canvas id="profilePlatformChart" style="width:100%;max-width:600;"></canvas>
                </div>
            </div>
        </div>

    </div>

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                const canvas = document.getElementById('profilePlatformChart');
                const ctx = canvas.getContext('2d');

                const chartData = @json($chartData);

                // Custom plugin to draw images next to labels
                const imagePlugin = {
                    id: 'imagePlugin',
                    afterDatasetsDraw: function(chart) {
                        const ctx = chart.ctx;
                        const meta = chart.getDatasetMeta(0);

                        const imgSize = 30;
                        chartData.photos.forEach(function(photoUrl, index) {
                            const dataPoint = meta.data[index];
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
                                ctx.arc(x + imgSize / 2, y + imgSize / 2, imgSize / 2, 0, Math.PI *
                                    2);
                                ctx.closePath();
                                ctx.clip(); // Clip to the circular area

                                // Draw the image
                                ctx.drawImage(img, x, y, imgSize, imgSize);
                                ctx.restore(); // Restore the context
                            };
                        });
                    }
                };

                // Create the chart
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Total views',
                            data: chartData.data,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                ticks: {
                                    autoSkip: false // Ensure all labels are displayed
                                }
                            }
                        }
                    },
                    plugins: [imagePlugin] // Add the custom image plugin
                });
            });
        </script>
    @endsection
</div>
