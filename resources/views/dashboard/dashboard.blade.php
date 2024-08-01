@extends('kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <a href="{{ route('dashboard') }}">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-bi bi-pin-angle"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Angkatan</h6>
                                        <h6 class="font-extrabold mb-0">{{$tahun_angkatan_terbanyak}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3 col-md-6">    
                    <a href="{{ route('dashboard') }}">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Mahasiswa</h6>
                                        <h6 class="font-extrabold mb-0">{{ $jumlah_mahasiswa }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </a>
                </div>            
                <div class="col-6 col-lg-3 col-md-6">
                    <a href="{{ route('dashboard') }}">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{$jumlah_mahasiswa_aktif}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <a href="{{ route('dashboard') }}">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Lulus</h6>
                                        <h6 class="font-extrabold mb-0">{{$jumlah_mahasiswa_lulus}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <form method="post" action="{{ url('dashboard')}}" id="filter-form">
                        @csrf
                            <div class="card-body">
                                <div class="chartCard">
                                    <div class="chartBox">
                                            <div class="box">
                                                <canvas id="BarChartSumAng2"width="600" height="400"></canvas>
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
    var query = @json($query);

    const labels = Object.keys(query);
    const data = Object.values(query);
    const ctx2 = document.getElementById('BarChartSumAng2').getContext('2d');
    const myChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
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
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
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
                borderWidth: 2 // Meningkatkan ketebalan garis border
            }]
        },
        options: {
            scales: {
                x: {
                    min: 0,
                    max: 4, // Menampilkan hanya 5 batang, dimulai dari indeks 0 (batang pertama) hingga indeks 4 (batang kelima).
                    ticks: {
                        font: {
                            size: 14, // Mengatur ukuran font sumbu x
                            weight: 'bold' // Mengatur ketebalan font sumbu x
                        },
                        callback: function(value, index, values) {
                            const maxLength = 15; // Maksimal karakter sebelum pecah baris
                            const label = this.getLabelForValue(value);
                            if (label.length > maxLength) {
                                let words = label.split(' ');
                                let lines = [];
                                let line = '';

                                words.forEach(word => {
                                    if ((line + word).length > maxLength) {
                                        lines.push(line);
                                        line = word + ' ';
                                    } else {
                                        line += word + ' ';
                                    }
                                });
                                lines.push(line.trim());
                                return lines;
                            } else {
                                return label;
                            }
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14, // Mengatur ukuran font sumbu y
                            weight: 'bold' // Mengatur ketebalan font sumbu y
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14, // Mengatur ukuran font label legenda
                            weight: 'bold' // Mengatur ketebalan font label legenda
                        }
                    }
                }
            }
        }
    });

    function scroller(event, chart) {
        const dataLength = chart.data.labels.length;
        console.log('scroll');
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
        chart.update(); // Memanggil metode update untuk memperbarui chart
    }

    ctx2.canvas.addEventListener('wheel', (e) => {
        scroller(e, myChart);
        e.preventDefault(); // Mencegah default scroll behavior
    });
</script>






@endsection
