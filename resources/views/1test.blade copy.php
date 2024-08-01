<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jumlah Mahasiswa Perjurusan</title>
</head>
<body>
    <div class="page-content">
        <section class="row">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Jumlah Mahasiswa Perjurusan</h5>
                            <form method="post" action="{{ route('test') }}">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <label for="year">Tahun</label>
                                        <select class="form-control" id="year" name="year">
                                            @foreach ($years as $yearItem)
                                                <option value="{{ $yearItem->year }}">{{ $yearItem->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary mt-3">Tampilkan Grafik</button>
                                    </div>
                                </div>
                            </form>
                            <div class="divider"></div>
                            <div class="chart-container">
                                <canvas id="BarChartSumNim" width="100px" height="45px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

    <script>
        // Simpan nilai-nilai filter saat halaman dimuat
        var yearSelect = document.getElementById('year');

        // Mengecek apakah ada nilai yang tersimpan di local storage
        var storedYear = localStorage.getItem('selectedYear');

        // Jika ada nilai yang tersimpan, set nilai-nilai filter sesuai dengan nilai yang tersimpan
        if (storedYear) {
            yearSelect.value = storedYear;
        }

        // Menyimpan nilai-nilai filter saat berubah
        yearSelect.addEventListener('change', function() {
            localStorage.setItem('selectedYear', yearSelect.value);
        });

        var query = @json($query);

        // Periksa apakah query berisi data yang valid
        console.log(query);

        var labels = Object.keys(query);
        var data = Object.values(query);
        var ctx = document.getElementById("BarChartSumNim").getContext("2d");

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: "Jumlah Mahasiswa",
                    data: data,
                    backgroundColor: [
                        '#C8E4B2',
                        '#B3A492',
                        '#219C90',
                        // Add more colors if needed
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y', // Setel sumbu untuk grafik horizontal
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        minBarLength: 5,
                    },
                },
                plugins: {
                    labels: {
                        render: 'value',
                    },
                },
            },
        });
    </script>
</body>
</html>
