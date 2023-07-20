@extends('layout.main')

@section('content')

	@if($data == null && $cekwaktu == true)
		<a class="btn btn-xl btn-primary" href="{{ route('user.createAbsen', Auth::user()->id) }}">
			Absen Masuk
		</a>
	@elseif($data != null and $data->absenkeluar == null)
		<a class="btn btn-xl btn-primary" href="{{ route('user.createAbsen', Auth::user()->id) }}">
			Absen Keluar
		</a>
	@endif
	
	<div class="row">
		<div class="py-3 col-xs-12 col-lg-12">
			  <div style="width: 50%; height: 25%; float: left;">
				<canvas id="myChart"></canvas>
			  </div>
			  
			  <div style="width: 30%; height: 25%; float: left;">
				<canvas id="myChart2"></canvas>
			  </div>
		</div>
		<div class="col-xs-12 col-lg-12">
			log absensi karyawan : {{ Auth::user()->name }}
			<table class="table table-bordered data-table" >
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Absen Masuk</th>
						<th>Absen Keluar</th>
						<th>Status Terlambat</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

@endsection

@push('javascript')

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>

	$(document).ready(function () {
		var table = $('.data-table').DataTable({
			processing: true,
			serverSide: false,
			ajax: "{{ route('History', Auth::user()->id) }}",
			columns: [
				{data: 'no', name: 'no'},
				{data: 'tanggal', name: 'tanggal'},
				{data: 'absenmasuk', name: 'absenmasuk'},
				{data: 'absenkeluar', name: 'absenkeluar'},
				{data: 'status', name: 'status'},
			],
		});
	});
</script>

<script>
	var chartColors = {
	  red: 'rgb(255, 99, 132)',
	  orange: 'rgb(255, 159, 64)',
	  yellow: 'rgb(255, 205, 86)',
	  green: 'rgb(75, 192, 192)',
	  blue: 'rgb(54, 162, 235)',
	  purple: 'rgb(153, 102, 255)',
	  grey: 'rgb(231,233,237)'
	};

	var Bar = new Chart(document.getElementById("myChart"), {
	  type: 'bar', 
	  data: 
	  {
		  labels: [
			'Ontime', 'Telat'
		  ],
		  datasets: [{
			label: 'Jumlah data',
			backgroundColor: [
				chartColors.red,
				chartColors.green,
			],
			data: [
				parseInt('{{ $chartbar["ontime"] }}'),
				parseInt('{{ $chartbar["telat"] }}'),
			]
		  }]
		}, 
	  options: {
		responsive: true,
		title: {
		  display: true,
		  text: "Perbandingan total jumlah datang tepat waktu dan telat dalam satu bulan"
		},
		tooltips: {
		  mode: 'index',
		  intersect: false
		},
		legend: {
		  display: false,
		},
		scales: {
		  xAxes: [{
			ticks: {
			  beginAtZero: true
			}
		  }],
		  yAxes: [{
			ticks: {
			  beginAtZero: true
			}
		  }],
		}
	  }
	});
	
	var Pie = new Chart(document.getElementById("myChart2"), {
	  type: 'pie', 
	  data: {
			labels: [
				@foreach($chartpie as $chartuser)"{{ $chartuser[0] }}", @endforeach
			],
			datasets: [
				{
					data: [@foreach($chartpie as $chartuser)
					{{ $chartuser[1] }}, 
				@endforeach],
					backgroundColor: [
						chartColors.red,
						chartColors.orange,
						chartColors.yellow,
						chartColors.green,
						chartColors.blue,
						chartColors.purple,
						chartColors.grey,
					]
				}]
		},
	});
</script>

@endpush