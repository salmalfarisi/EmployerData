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
				<input type="text" class="form-control" name="nama_karyawan" value="{{ is_null($data->id) ? '' : $data->nama_karyawan }}" <?php if($title == 'detail'){echo 'readonly disabled';}?>>
				<small class="text-danger">@if($errors->has('nama_karyawan')) {{ $errors->first('nama_karyawan') }}@endif</small>
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
@endsection