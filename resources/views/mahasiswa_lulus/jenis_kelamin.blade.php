@extends('kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="container">
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Jumlah Mahasiswa Lulus Berdasarkan Jenis Kelamin</h4>
                            </div>
                            <form method="GET" action="{{ route('jumlah_mahasiswa_lulus_kelamin') }}" id="filter-form">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="year">Tahun Angkatan:</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Pilih Tahun</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year->year }}" {{ request('year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jurusan">Jurusan:</label>
                                        <select name="jurusan" id="jurusan" class="form-control">
                                            <option value="">Pilih Jurusan</option>
                                            @foreach($allJurusan as $jur)
                                            <option value="{{ $jur->jurusan }}" {{ $selectedJurusan == $jur->jurusan ? 'selected' : '' }}>{{ $jur->jurusan }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="card-body">
                                    <div class="chartCard">
                                        <div class="chartBox">
                                            <div class="box">
                                            @if(count($query) > 0)
                                                <canvas id="BarChartSum2" width="600" height="400"></canvas>
                                            @else
                                                <p>No data available for the selected year and jurusan.</p>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>  
                </div>
            </div>
        </section>
    </div>
</div>
<style>
    .chartMenu {
        width: 100%;
        height: 40px;
    }

    .theme-dark.chartCard {
        width: 100%;
        height: calc(90vh - 30px); /* Meningkatkan tinggi card */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chartBox {
        width: 100%;
        padding: 20px;
        border-radius: 20px;
        border: solid 3px rgba(0, 95, 153, 0.72);
        background: white;
        display: flex;
        flex-direction: column;
        height: 80%; /* Meningkatkan tinggi chart box */
    }

    .box {
        width: 100%;
        height: 800px; /* Meningkatkan tinggi canvas */
        flex: 1;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>

<script>
    $(document).ready(function() {
        var query = @json($query);

        if (query.length > 0) {
            var labels = query.map(item => item.kelamin);
            var data = query.map(item => item.jumlah_mahasiswa);

            const ctx2 = document.getElementById('BarChartSum2').getContext('2d');
            const myChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Jumlah Mahasiswa",
                        data: data,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(0, 255, 255, 0.8)',
                            'rgba(255, 0, 255, 0.8)',
                            'rgba(128, 128, 0, 0.8)',
                            'rgba(0, 128, 128, 0.8)',
                            'rgba(128, 0, 128, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 0.1)',
                            'rgba(255, 99, 132, 0.1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(0, 255, 255, 1)',
                            'rgba(255, 0, 255, 1)',
                            'rgba(128, 128, 0, 1)',
                            'rgba(0, 128, 128, 1)',
                            'rgba(128, 0, 128, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            min: 0,
                            max: 1,
                            ticks: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            function scroller(event, chart) {
                const dataLength = chart.data.labels.length;
                if (event.deltaY > 0) {
                    if (chart.options.scales.x.max < dataLength - 1) {
                        chart.options.scales.x.min += 1;
                        chart.options.scales.x.max += 1;
                    }
                } else if (event.deltaY < 0) {
                    if (chart.options.scales.x.min > 0) {
                        chart.options.scales.x.min -= 1;
                        chart.options.scales.x.max -= 1;
                    }
                }
                chart.update();
            }

            ctx2.canvas.addEventListener('wheel', (e) => {
                scroller(e, myChart);
            });
        } else {
            console.log('No data available for the selected year and jurusan.');
        }
    });
</script>






@endsection
