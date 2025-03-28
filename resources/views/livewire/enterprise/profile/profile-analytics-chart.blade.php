<div>
    <div class="row mb-5">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Profile Analytics</h5>
                    <div class="row">
                        <div class="col-4 ms-auto">
                            <select class="form-select form-select-sm" wire:model="days">
                                <option selected>Select Days</option>
                                <option value="7">Last 7 Days</option>
                                <option value="14"> Last 14 Days </option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 3 Months</option>
                            </select>
                        </div>
                    </div>
                    <canvas id="profileAnalyticsChart" style="width:100%;max-width:600;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var data = @json($analyticsData);
            chartData(data, '{{ $days }}');
        });

        document.addEventListener('refreshChart', event => {
            var data = event.detail.analyticsData
            chartData(data, event.detail.days);
        });

        function chartData(data, days = 7) {
            new Chart("profileAnalyticsChart", {
                type: 'bar',
                data: {
                    labels: ['Profile Views', 'Total Clicks', 'Total Platforms', 'Total Groups'],
                    datasets: [{
                        label: 'Profile Analytics',
                        borderRadius: 15,
                        data: [
                            data[0]['profileViews'],
                            data[1]['total_clicks'],
                            data[2]['total_platforms'],
                            data[3]['total_groups']
                        ],
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)', 'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)', 'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
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
                }
            });
        }
    </script>
</div>
