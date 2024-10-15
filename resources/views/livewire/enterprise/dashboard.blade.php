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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                const ctx = document.getElementById('profilePlatformChart').getContext('2d');
                const chartData = @json($chartData);

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
                            }
                        }
                    }
                });
            });
        </script>
    @endsection
</div>
