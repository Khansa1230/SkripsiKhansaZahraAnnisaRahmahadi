@extends('kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading">
    <!-- Basic Tables start -->

    <section class="section">
    

    <div class="card">
    <form method="GET" action="{{ route('prediksi') }}" id="filter-form">
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
            <div class="form-group">
                <label for="selectedPercentage">Selected Percentage:</label>
                <select class="form-control" id="selectedPercentage" name="selectedPercentage" required>
                    <option value="100" {{ $selectedPercentage == 100 ? 'selected' : '' }}>100%</option>
                    <option value="90" {{ $selectedPercentage == 90 ? 'selected' : '' }}>90%</option>
                    <option value="80" {{ $selectedPercentage == 80 ? 'selected' : '' }}>80%</option>
                    <option value="70" {{ $selectedPercentage == 70 ? 'selected' : '' }}>70%</option>
                    <option value="60" {{ $selectedPercentage == 60 ? 'selected' : '' }}>60%</option>
                    <option value="50" {{ $selectedPercentage == 50 ? 'selected' : '' }}>50%</option>
                    <option value="40" {{ $selectedPercentage == 40 ? 'selected' : '' }}>40%</option>
                    <option value="30" {{ $selectedPercentage == 30 ? 'selected' : '' }}>30%</option>
                    <option value="20" {{ $selectedPercentage == 20 ? 'selected' : '' }}>20%</option>
                    <option value="10" {{ $selectedPercentage == 10 ? 'selected' : '' }}>10%</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body">
        @if (!empty($result5['query5']) || !empty($result6['query6']) || !empty($result7['query7']) || !empty($result8['query8']))
        <div class="card-header">
            Data Latih - {{ $selectedPercentage }}%
        </div>
        <div class="table-responsive">
            <table class="table" id="table2">
                <thead>
                    <tr>
                        <th>Jumlah Mahasiswa</th>
                        <th></th>
                        <th>Tepat Lulus</th>
                        <th>Tidak Tepat Lulus</th>
                        <th>Belum Lulus</th>
                        <th>Entropy</th>
                        <th>Gain</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Query 1: Status Kelulusan --}}
                <tr>
                    <td>Total</td>
                    <td>{{ $result5['query5']->sum('jumlah_mahasiswa2') }}</td>
                    <td>{{ $result5['query5']->sum('Tepat_Lulus_prediksi') }}</td>
                    <td>{{ $result5['query5']->sum('Tidak_Tepat_Lulus_prediksi') }}</td>
                    <td>{{ $result5['query5']->sum('Belum_Lulus_prediksi') }}</td>
                    <td>{{ $result5['entropy2'] }}</td>
                    <td></td>
                </tr>
                    {{-- Query 2: Status IPK --}}
                    <tr>
                        <td>Indeks Prestasi Kumulatif</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp

                    @foreach($result6['query6'] as $index => $data)
                        <tr>
                            <td>{{ $data['status_ipk'] }}</td>
                            <td>{{ $data['jumlah_mahasiswa2'] }}</td>
                            <td>{{ $data['Tepat_Lulus_prediksi'] }}</td>
                            <td>{{ $data['Tidak_Tepat_Lulus_prediksi'] }}</td>
                            <td>{{ $data['Belum_Lulus_prediksi'] }}</td>
                            <td>{{ $data['entropy2'] }}</td>
                            <td>
                                @if ($firstRow)
                                    {{ $result6['gain_ipk2'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                    @php
                                        $firstRow = false;
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach


                    {{-- Query 3: Jabodetabek vs Luar Jabodetabek --}}
                    <tr>
                        <td>Jabodetabek</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp
                    @foreach($result7['query7'] as $data)
                    <tr>
                        <td>{{ $data['jabodetabek'] }}</td>
                        <td>{{ $data['jumlah_mahasiswa2'] }}</td>
                        <td>{{ $data['Tepat_Lulus_prediksi'] }}</td>
                        <td>{{ $data['Tidak_Tepat_Lulus_prediksi'] }}</td>
                        <td>{{ $data['Belum_Lulus_prediksi'] }}</td>
                        <td>{{ $data['entropy2'] }}</td>
                        <td>
                            @if ($firstRow)
                                {{ $result7['gain_jabodetabek2'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                @php
                                    $firstRow = false;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    {{-- Query 4: Tahun Lulus --}}
                    <tr>
                        <td>Tahun Lulus</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp
                    @foreach($result8['query8'] as $data)
                    <tr>
                        <td>{{ $data['tahun_lulus'] }}</td>
                        <td>{{ $data['jumlah_mahasiswa2'] }}</td>
                        <td>{{ $data['Tepat_Lulus_prediksi'] }}</td>
                        <td>{{ $data['Tidak_Tepat_Lulus_prediksi'] }}</td>
                        <td>{{ $data['Belum_Lulus_prediksi'] }}</td>
                        <td>{{ $data['entropy2'] }}</td>
                        <td>
                            @if ($firstRow)
                                {{ $result8['gain_tahun_lulus2'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                @php
                                    $firstRow = false;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>Tidak ada data yang tersedia untuk ditampilkan.</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="card-header">Highest Gain Information</div>
        <div class="table-responsive">
            <table class="table" id="table2">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Highest Gain Type</td>
                        <td>{{ $highest_gain_type_asli }}</td>
                    </tr>
                    <tr>
                        <td>Highest Gain Value</td>
                        <td>{{ $highest_gain_value_asli }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-header">Performance Metrics</div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Precision</th>
                        <th>Accuracy</th>
                        <th>Recall</th>
                        <th>F1 Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ round($precision_prediksi) . '%' }}</td>
                        <td>{{ round($accuracy_prediksi) . '%' }}</td>
                        <td>{{ round($recall_prediksi) . '%' }}</td>
                        <td>{{ round($f1_score_prediksi) . '%' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if (!empty($result1['query1']) || !empty($result2['query2']) || !empty($result3['query3']) || !empty($result4['query4']))
        <div class="card-header">
            Data Latih - {{ $selectedPercentage }}%
        </div>
        <div class="table-responsive">
            <table class="table" id="table2">
                <thead>
                    <tr>
                        <th>Jumlah Mahasiswa</th>
                        <th></th>
                        <th>Tepat Lulus</th>
                        <th>Tidak Tepat Lulus</th>
                        <th>Belum Lulus</th>
                        <th>Entropy</th>
                        <th>Gain</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Query 1: Status Kelulusan --}}
                <tr>
                    <td>Total</td>
                    <td>{{ $result1['query1']->sum('jumlah_mahasiswa1') }}</td>
                    <td>{{ $result1['query1']->sum('Tepat_Lulus') }}</td>
                    <td>{{ $result1['query1']->sum('Tidak_Tepat_Lulus') }}</td>
                    <td>{{ $result1['query1']->sum('Belum_Lulus') }}</td>
                    <td>{{ $result1['entropy'] }}</td>
                    <td></td>
                </tr>
                    {{-- Query 2: Status IPK --}}
                    <tr>
                        <td>Indeks Prestasi Kumulatif</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp

                    @foreach($result2['query2'] as $index => $data)
                        <tr>
                            <td>{{ $data['status_ipk'] }}</td>
                            <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                            <td>{{ $data['Tepat_Lulus'] }}</td>
                            <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                            <td>{{ $data['Belum_Lulus'] }}</td>
                            <td>{{ $data['entropy'] }}</td>
                            <td>
                                @if ($firstRow)
                                    {{ $result2['gain_ipk'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                    @php
                                        $firstRow = false;
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach


                    {{-- Query 3: Jabodetabek vs Luar Jabodetabek --}}
                    <tr>
                        <td>Jabodetabek</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp
                    @foreach($result3['query3'] as $data)
                    <tr>
                        <td>{{ $data['jabodetabek'] }}</td>
                        <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                        <td>{{ $data['Tepat_Lulus'] }}</td>
                        <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                        <td>{{ $data['Belum_Lulus'] }}</td>
                        <td>{{ $data['entropy'] }}</td>
                        <td>
                            @if ($firstRow)
                                {{ $result3['gain_jabodetabek'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                @php
                                    $firstRow = false;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    {{-- Query 4: Tahun Lulus --}}
                    <tr>
                        <td>Tahun Lulus</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $firstRow = true;
                    @endphp
                    @foreach($result4['query4'] as $data)
                    <tr>
                        <td>{{ $data['tahun_lulus'] }}</td>
                        <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                        <td>{{ $data['Tepat_Lulus'] }}</td>
                        <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                        <td>{{ $data['Belum_Lulus'] }}</td>
                        <td>{{ $data['entropy'] }}</td>
                        <td>
                            @if ($firstRow)
                                {{ $result4['gain_tahun_lulus'] }} <!-- Menampilkan gain_ipk hanya sekali -->
                                @php
                                    $firstRow = false;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>Tidak ada data yang tersedia untuk ditampilkan.</p>
        @endif
    </div>
</div>


@if ( !empty($highest_gain_type_latih) && !empty($highest_gain_value_latih) && isset($precision_latih) && isset($accuracy_latih) && isset($recall_latih) && isset($f1_score_latih))
    <div class="card">
        <div class="card-body">
            <div class="card-header">Highest Gain Information</div>
            <div class="table-responsive">
                <table class="table" id="table2">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Highest Gain Type</td>
                            <td>{{ $highest_gain_type_latih }}</td>
                        </tr>
                        <tr>
                            <td>Highest Gain Value</td>
                            <td>{{ $highest_gain_value_latih }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-header">Performance Metrics</div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Precision</th>
                            <th>Accuracy</th>
                            <th>Recall</th>
                            <th>F1 Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ round($precision_latih, 2) . '%' }}</td>
                            <td>{{ round($accuracy_latih, 2) . '%' }}</td>
                            <td>{{ round($recall_latih, 2) . '%' }}</td>
                            <td>{{ round($f1_score_latih, 2) . '%' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
















        
    </section>
</div>
@endsection
