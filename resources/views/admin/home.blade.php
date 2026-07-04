@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Dashboard Home
</h3>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h5 class="mb-0">
        Statistik Presensi
    </h5>

    <form method="GET" action="{{ url('/admin/home') }}">

        <select
            name="periode"
            class="form-select"
            onchange="this.form.submit()">

            <option value="hari_ini"
            {{ $periode=='hari_ini' ? 'selected' : '' }}>
                Hari Ini
            </option>

            <option value="7_hari"
            {{ $periode=='7_hari' ? 'selected' : '' }}>
                7 Hari Terakhir
            </option>

            <option value="30_hari"
            {{ $periode=='30_hari' ? 'selected' : '' }}>
                30 Hari Terakhir
            </option>

            <option value="bulan"
            {{ $periode=='bulan' ? 'selected' : '' }}>
                Bulan Ini
            </option>

            <option value="tahun"
            {{ $periode=='tahun' ? 'selected' : '' }}>
                Tahun Ini
            </option>

            <option value="semua"
            {{ $periode=='semua' ? 'selected' : '' }}>
                Semua Data
            </option>

        </select>

    </form>

</div>


<div class="row">

    <!-- Total Anak Magang -->
    <div class="col-md-3 mb-3">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h6>Total Anak Magang</h6>
                <h2>{{ $totalMahasiswa }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Hadir -->
    <div class="col-md-3 mb-3">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h6>Total Hadir</h6>
                <h2>{{ $totalHadir }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Alpa -->
    <div class="col-md-3 mb-3">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h6>Total Alpa</h6>
                <h2>{{ $totalAlpa }}</h2>
            </div>
        </div>
    </div>

    <!-- Total Izin / Sakit -->
    <div class="col-md-3 mb-3">
        <div class="card card-dashboard">
            <div class="card-body text-center">
                <h6>Izin / Sakit</h6>
                <h2>{{ $totalIzinSakit }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="card card-dashboard mt-4">

    <div class="card-header text-white"
        style="background:#043277;">

    Data Presensi
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>

            </thead>

            <tbody>

                @forelse($presensiHariIni as $item)

                <tr>

                    <td>

{{ ($presensiHariIni->currentPage()-1) * $presensiHariIni->perPage() + $loop->iteration }}

</td>

                    <td>{{ $item->mahasiswa->user->name }}</td>
                    
                    <td>{{ $item->jam_masuk ?? '-' }}</td>

                    <td>{{ $item->jam_pulang ?? '-' }}</td>

                    <td>

                        @if($item->status == 'hadir')

                            <span class="badge bg-success">Hadir</span>

                        @elseif($item->status == 'alpa')

                            <span class="badge bg-danger">Alpa</span>

                        @elseif($item->status == 'izin')

                            <span class="badge bg-warning text-dark">Izin</span>

                        @elseif($item->status == 'sakit')

                            <span class="badge bg-info text-dark">Sakit</span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center">

                        Belum ada data presensi hari ini.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

{{-- batas kode tulisan __results ???--}}

<div class="d-flex justify-content-end mt-3">

    {{ $presensiHariIni->links() }}

</div>
{{-- batas kode tulisan __results>??? --}}


    </div>
</div>

<div class="row mt-4">

    <!-- Pie Chart -->
    <div class="col-lg-6 mb-4">

        <div class="card card-dashboard h-100">

            <div class="card-header text-white"
                style="background:#043277;">

                Diagram Presentase Presensi
                
            </div>

            <div class="card-body text-center">

                <div style="width:350px; height:350px; margin:auto;">

                    <canvas id="chartPresensi"></canvas>

                </div>

            </div>

        </div>

    </div>

    <!-- Bar Chart -->
    <div class="col-lg-6 mb-4">

        <div class="card card-dashboard h-100">

            <div class="card-header text-white"
                style="background:#043277;">

                Diagram Jumlah Presensi

            </div>
            
            <div class="card-body">

                <div style="height:350px;">

                    <canvas id="barChartPresensi"></canvas>

                 </div>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>

const ctx = document.getElementById('chartPresensi'); 

Chart.register(ChartDataLabels);

//PUNYA PIE CHART
new Chart(ctx,{

    type:'doughnut',

    data:{

        labels:[
            'Hadir',
            'Alpa',
            'Izin / Sakit'
        ],

        datasets:[{

            label:'Jumlah Presensi',

            data:[
                {{ $totalHadir }},
                {{ $totalAlpa }},
                {{ $totalIzinSakit }}
            ],

            backgroundColor:[
                '#198754',
                '#dc3545',
                '#ffc107'
            ],

            borderColor:[
                '#ffffff',
                '#ffffff',
                '#ffffff'
            ],

            borderWidth:2

        }]

    },

    options:{

        responsive:true,

           maintainAspectRatio:false,

    plugins:{

        legend:{
            position:'bottom'
        },

        title:{
            display:true,
            text:'Distribusi Status Presensi Mahasiswa'
        },

        datalabels:{

            color:'#fff',

            font:{
                weight:'bold',
                size:16
            },

            formatter:(value,context)=>{

                let data=context.chart.data.datasets[0].data;

                let total=data.reduce((a,b)=>a+b,0);

                if(total==0){
                    return '';
                }

                let percentage=(value/total*100).toFixed(1);

                return percentage+'%';

            }

        }

    }

},

plugins:[ChartDataLabels]


});
//BATAS INI PIE CHART

// PUNYA BAR CHART
const ctxBar = document.getElementById('barChartPresensi');

new Chart(ctxBar,{

    type:'bar',

    data:{

        labels:[
            'Hadir',
            'Alpa',
            'Izin / Sakit'
        ],

        datasets:[{

            label:'Jumlah Mahasiswa',

            data:[
                {{ $totalHadir }},
                {{ $totalAlpa }},
                {{ $totalIzinSakit }}
            ],

            backgroundColor:[
                '#198754',
                '#dc3545',
                '#ffc107'
            ],

            borderRadius:8

        }]

    },

    options:{

        responsive:true,

        maintainAspectRatio:false,

        plugins:{

    legend:{
        display:false
    },

    title:{
        display:true,
        text:'Jumlah Presensi'
    },

    tooltip:{

        callbacks:{

            label:function(context){

                let data=context.dataset.data;

                let total=data.reduce((a,b)=>a+b,0);

                let value=context.raw;

                let persen=(value/total*100).toFixed(1);

                return value+' Presensi ('+persen+'%)';

            }

        }

    },

    datalabels:{

        anchor:'end',

        align:'top',

        color:'#000',

        font:{
            size:14,
            weight:'bold'
        },

        formatter:function(value,context){

            let data=context.chart.data.datasets[0].data;

            let total=data.reduce((a,b)=>a+b,0);

            if(total==0){

                return '';

            }

            return ((value/total)*100).toFixed(1)+'%';

        }

    }

},

        scales:{

            y:{

                beginAtZero:true,

                ticks:{
                    precision:0
                }

            }

        }

    },

plugins:[ChartDataLabels]

});
//BATAS SINI CBAR CHART

</script>

@endsection