@extends('layout.main')

@section('content')
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title">{{ $title }}</h3>
		</div>
		<form action="{{ is_null($data->id) ? route('karyawan.store') : route('karyawan.update', $data->id) }}" class="form-horizontal" method="post">
		  @csrf
		  <div class="card-body">
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Nama Karyawan</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<input type="text" class="form-control" name="nama_karyawan" value="{{ is_null($data->id) ? '' : $data->name }}" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
				<small class="text-danger">@if($errors->has('name')) {{ $errors->first('name') }}@endif</small>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Jabatan</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<select class="custom-select" name="jabatan" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
					@foreach($jabatan as $pilihan)
						<option value="{{ $pilihan->id }}" <?php if($pilihan->id == $data->jabatan){ echo 'selected'; } ?>>{{ $pilihan->jabatan }}</option>
					@endforeach
				</select>
				<small class="text-danger">@if($errors->has('jabatan')) {{ $errors->first('jabatan') }}@endif</small>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Tanggal Lahir</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<input type="date" class="form-control" name="tgl_lahir" value="{{ is_null($data->id) ? '' : $data->tgl_lahir }}" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
				<small class="text-danger">@if($errors->has('tgl_lahir')) {{ $errors->first('tgl_lahir') }}@endif</small>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Alamat</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<textarea type="text" class="form-control" name="alamat" rows="5" <?php if($title == 'detail'){echo 'readonly disabled';}?>>{{ is_null($data->id) ? '' : $data->alamat }}</textarea>
				<small class="text-danger">@if($errors->has('alamat')) {{ $errors->first('alamat') }}@endif</small>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Kantor Cabang</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<select class="custom-select" name="kantor_cabang" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
					@foreach($kantor_cabang as $pilihan)
						<option value="{{ $pilihan->id }}" <?php if($pilihan->id == $data->kantor_cabang){ echo 'selected'; } ?> >{{ $pilihan->nama_kantor }}</option>
					@endforeach
				</select>
				<small class="text-danger">@if($errors->has('kantor_cabang')) {{ $errors->first('kantor_cabang') }}@endif</small>
			  </div>
			</div>
			@if($title == 'detail')
			<div class="form-group row">
			  <label class="col-xs-6 col-sm-6 col-md-2 col-form-label">Status</label>
			  <div class="col-xs-6 col-sm-6 col-md-10">
				<input type="text" class="form-control" value="{{ ($data->status == true ? 'Aktif' : 'Tidak Aktif') }}" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
				<small class="text-danger">@if($errors->has('name')) {{ $errors->first('name') }}@endif</small>
			  </div>
			</div>
			@endif
		  </div>
		  <!-- /.card-body -->
		  <div class="card-footer">
			@if($title == 'create' || $title == 'edit')
			<button type="submit" class="btn btn-primary float-right">{{ ($title == 'create') ? 'Tambah' : 'Update' }}</button>
			@endif
			<a href="{{ route('karyawan.index') }}" class="btn btn-danger">Kembali</a>
		  </div>
		  <!-- /.card-footer -->
		</form>
    </div>
	
	@if($title == 'detail')
	
	<div class="row">
		<div class="col-xs-12 col-lg-12">
			  <div style="width: 50%; height: 25%; float: left;">
				<canvas id="myChart"></canvas>
			  </div>
			  
			  <div style="width: 50%; height: 25%; float: left;">
				<canvas id="myChart2"></canvas>
			  </div>
			  
			  <div style="width: 50%; height: 50%; float: left;">
				<canvas id="myChart3"></canvas>
			  </div>
		</div>
		<div class="col-xs-12 col-lg-12">
			log absensi karyawan : {{ $data->name }}
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
	
	@endif
	
@endsection

@push('javascript')

@if($title == 'detail')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--https://code.jquery.com/jquery-3.7.0.js
https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js
https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js
https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js
https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js-->
<script>

	$(document).ready(function () {
		var table = $('.data-table').DataTable({
			processing: true,
			serverSide: false,
			searching:false,
			ajax: "{{ route('History', $data->id) }}",
			columns: [
				{data: 'no', name: 'no'},
				{data: 'tanggal', name: 'tanggal'},
				{data: 'absenmasuk', name: 'absenmasuk'},
				{data: 'absenkeluar', name: 'absenkeluar'},
				{data: 'status', name: 'status'},
			],
			buttons:[
				'excel','pdf'
			]
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
		  text: "Perbandingan jumlah datang tepat waktu dan telat"
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
	
	var Line = new Chart(document.getElementById("myChart3"), {
		type: 'line',
		data: {
			labels: [@foreach($chartline as $chartuser)"{{ $chartuser[0] }}", @endforeach],
			datasets: [{
				label: "Jam",
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				fill: false,
				data: [
					@foreach($chartline as $chartuser)"{{ $chartuser[1] }}", @endforeach
				],
			},]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Log absensi karyawan'
			},
			scales: {
				xAxes: [{
					display: true,
		  scaleLabel: {
			display: true,
			labelString: 'Date'
		  },
			
				}],
				yAxes: [{
					display: true,
					//type: 'logarithmic',
		  scaleLabel: {
							display: true,
							labelString: 'Index Returns'
						},
						ticks: {
							min: 0,
							max: 24,

							// forces step size to be 5 units
							stepSize: 3
						}
				}]
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
@endif

@endpush