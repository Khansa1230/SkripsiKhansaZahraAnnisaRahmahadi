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
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($entropylulus)
                <div class="card-header">
                    Entropy Total Status Lulus
                </div>
                <div class="table-responsive">
                    <table class="table" id="table2">
                        <thead>
                            <tr>
                                <th>Jumlah Kasus</th>
                                <th></th>
                                <th>Tepat Lulus</th>
                                <th>Tidak Tepat Lulus</th>
                                <th>Belum Lulus</th>
                                <th>Entropy</th>
                                <th>Gain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entropylulus as $data)
                            <tr>
                                <td>Total</td>
                                <td>{{ $data['jumlah_mahasiswa'] }}</td>
                                <td>{{ $data['Tepat_Lulus'] }}</td>
                                <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                                <td>{{ $data['Belum_Lulus'] }}</td>
                                <td>{{ number_format($data['entropy'], 3) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <th>Indeks Prestasi Kumulatif</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($entropy_ipk as $index => $data)
                            <tr>
                                <td>{{ $data['status_ipk'] }}</td>
                                <td>{{ $data['jumlah_mahasiswa'] }}</td>
                                <td>{{ $data['Tepat_Lulus'] }}</td>
                                <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                                <td>{{ $data['Belum_Lulus'] }}</td>
                                <td>{{ number_format($data['entropy'], 3) }}</td>
                                <td>
                                    @if ($index == 0)
                                    {{ number_format($data['gain_ipk'], 3) }} <!-- Menampilkan gain_ipk hanya sekali -->
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <th>Jabodetabek</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($entropy_jabo as $index => $data)
                            <tr>
                                <td>{{ $data['jabodetabek'] }}</td>
                                <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                                <td>{{ $data['Tepat_Lulus'] }}</td>
                                <td>{{ $data['Belum_Lulus'] }}</td>
                                <td>{{ $data['jumlah_mahasiswa'] }}</td>
                                <td>{{ number_format($data['entropy'], 3) }}</td>
                                <td>
                                    @if ($index == 0)
                                    {{ number_format($data['gain_jabo'], 3) }} <!-- Menampilkan gain_talus hanya sekali -->
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <th>Tahun Lulus</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($entropy_talus as $index => $data)
                            <tr>
                                <td>{{ $data['tahun_lulus'] }}</td>
                                <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                                <td>{{ $data['Tepat_Lulus'] }}</td>
                                <td>{{ $data['Belum_Lulus'] }}</td>
                                <td>{{ $data['jumlah_mahasiswa'] }}</td>
                                <td>{{ number_format($data['entropy'], 3) }}</td>
                                <td>
                                    @if ($index == 0)
                                    {{ number_format($data['gain_talus'], 3) }} <!-- Menampilkan gain_talus hanya sekali -->
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        <div class="card">
    <div class="card-body">
        <h5 class="card-title">Evaluation Metrics</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Highest Gain Type</td>
                        <td>{{ isset($result['highest_gain_type']) ? $result['highest_gain_type'] : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Highest Gain Value</td>
                        <td>{{ isset($result['highest_gain_value']) ? $result['highest_gain_value'] : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
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
                        <td>{{ isset($result['precision']) ? $result['precision'] : 'N/A' }}</td>
                        <td>{{ isset($result['accuracy']) ? $result['accuracy'] : 'N/A' }}</td>
                        <td>{{ isset($result['recall']) ? $result['recall'] : 'N/A' }}</td>
                        <td>{{ isset($result['f1_score']) ? $result['f1_score'] : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    </section>
</div>
@endsection


@if ($selectedPercentage != 100)
<div class="card">
    <div class="card-body">
        @if (!empty($resultsisa1['query1']) || !empty($resultsisa2['query2']) || !empty($resultsisa3['query3']) || !empty($resultsisa4['query4']))
            <div class="card-header">
                Data Uji - {{100 - $selectedPercentage }}%
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
                        <td>{{ $resultsisa1['query1']->sum('jumlah_mahasiswa1') }}</td>
                        <td>{{ $resultsisa1['query1']->sum('Tepat_Lulus') }}</td>
                        <td>{{ $resultsisa1['query1']->sum('Tidak_Tepat_Lulus') }}</td>
                        <td>{{ $resultsisa1['query1']->sum('Belum_Lulus') }}</td>
                        <td>{{ $resultsisa1['entropy'] }}</td>
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

                        @foreach($resultsisa2['query2'] as $index => $data)
                            <tr>
                                <td>{{ $data['status_ipk'] }}</td>
                                <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                                <td>{{ $data['Tepat_Lulus'] }}</td>
                                <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                                <td>{{ $data['Belum_Lulus'] }}</td>
                                <td>{{ $data['entropy'] }}</td>
                                <td>
                                    @if ($firstRow)
                                        {{ $resultsisa2['gain_ipk_sisa'] }} <!-- Menampilkan gain_ipk hanya sekali-->
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
                        @foreach($resultsisa3['query3'] as $data)
                        <tr>
                            <td>{{ $data['jabodetabek'] }}</td>
                            <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                            <td>{{ $data['Tepat_Lulus'] }}</td>
                            <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                            <td>{{ $data['Belum_Lulus'] }}</td>
                            <td>{{ $data['entropy'] }}</td>
                            <td>
                                @if ($firstRow)
                                    {{ $resultsisa3['gain_jabodetabek_sisa'] }} <!--Menampilkan gain_ipk hanya sekali -->
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
                        @foreach($resultsisa4['query4'] as $data)
                        <tr>
                            <td>{{ $data['tahun_lulus'] }}</td>
                            <td>{{ $data['jumlah_mahasiswa1'] }}</td>
                            <td>{{ $data['Tepat_Lulus'] }}</td>
                            <td>{{ $data['Tidak_Tepat_Lulus'] }}</td>
                            <td>{{ $data['Belum_Lulus'] }}</td>
                            <td>{{ $data['entropy'] }}</td>
                            <td>
                                @if ($firstRow)
                                    {{ $resultsisa4['gain_tahun_lulus_sisa'] }} <!-- Menampilkan gain_ipk hanya sekali -->
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
@endif 


@if ($selectedPercentage != 100 && !empty($highest_gain_type) && !empty($highest_gain_value) && isset($precision) && isset($accuracy) && isset($recall) && isset($f1_score))
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
                            <td>{{ $highest_gain_type }}</td>
                        </tr>
                        <tr>
                            <td>Highest Gain Value</td>
                            <td>{{ $highest_gain_value }}</td>
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
                            <td>{{ round($precision, 2) . '%' }}</td>
                            <td>{{ round($accuracy, 2) . '%' }}</td>
                            <td>{{ round($recall, 2) . '%' }}</td>
                            <td>{{ round($f1_score, 2) . '%' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
