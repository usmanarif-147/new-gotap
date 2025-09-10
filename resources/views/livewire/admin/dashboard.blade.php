<div>
    <div class="row mb-5">

        <x-custom.dashboard-card background="bg-label-info" icon="bx bx-user-pin" title="Users" :total="$users" />
        <x-custom.dashboard-card background="bg-label-warning" icon="bx bx-news" title="Applications" :total="$applications" />
        <x-custom.dashboard-card background="bg-label-danger" icon="bx bx-category" title="Categories" :total="$categories" />
        <x-custom.dashboard-card background="bg-label-success" icon="bx bx-shape-square" title="Platforms"
            :total="$platforms" />
        <x-custom.dashboard-card background="bg-label-secondary" icon="bx bx-credit-card-alt" title="Cards"
            :total="$cards" />

        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <select class="form-select form-select-sm" wire:model="days">
                                <option selected>Select Days</option>
                                <option value="7">Last 7 Days</option>
                                <option value="14"> Last 14 Days </option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 3 Months</option>
                            </select>
                        </div>
                    </div>
                    <canvas id="registrations" style="width:100%;max-width:600;"></canvas>
                </div>
            </div>
        </div>

    </div>

    @section('script')
        <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
        </script>
        <script>
            $(document).ready(function() {
                var data = JSON.parse(@json($registrations));
                chartData(data, '{{ $days }}');
            });

            document.addEventListener('showData', event => {
                var data = JSON.parse(event.detail.registrations)
                chartData(data, event.detail.days);
            });

            function chartData(data, days = 7) {
                // registrations graph
                let registrations = data;

                let dates = [];
                let users = [];
                for (let i = 0; i < registrations.length; i++) {
                    dates.push(registrations[i].created_date);
                    users.push(registrations[i].user_count);
                }
                var xValues = dates;
                var yValues_a = users;
                var barColors = ["green"];
                new Chart("registrations", {
                    type: "bar",
                    data: {
                        labels: xValues,
                        datasets: [{
                            label: 'Total Registrations',
                            backgroundColor: barColors[0],
                            data: yValues_a
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1,
                                    min: 0,
                                    callback: function(value, index, values) {

                                        return value;
                                    }
                                }
                            }]
                        },
                        legend: {
                            display: true
                        },
                        events: [],
                        title: {
                            display: true,
                            text: "Last " + days + " days registrations"
                        }
                    }
                });
            }
        </script>
    @endsection

</div>
