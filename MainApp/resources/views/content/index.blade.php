@extends('layout.main')

@section('content')
	 
	<div class="card">
		<div class="card-body p-0">
			<table class="table">
				<thead>
					<tr>
						<th cols="5%">No</th>
						<th cols="30%">Nama</th>
						<th cols="20%">Jabatan</th>
						<th cols="20%">Kantor</th>
						<th cols="5%">Status</th>
						<th cols="20%">Pilihan</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = ($data->currentpage()-1)* $data->perpage() + 1;?>
					@foreach($data as $datas)
						<tr>
							<td>{{ $i++ }}</td>
							<td>{{ $datas->nama_karyawan }}</td>
							<td>{{ $datas->jabatan }}</td>
							<td>{{ $datas->nama_kantor }}</td>
							<td>{!! ($datas->status == true ? 'Aktif' : 'Tidak Aktif') !!}</td>
							<td>
								<div class="d-flex justify-content-center">
									<a href="{{ route('karyawan.show', $datas->id) }}" class="btn btn-sm btn-info mx-1">Detail</a>
									<a href="{{ route('karyawan.edit', $datas->id) }}" class="btn btn-sm btn-warning mx-1">Edit</a>
									<button type="button" class="btn btn-sm btn-danger mx-1" data-toggle="modal" data-target="#modal-danger" data-desc="Apakah anda yakin ingin menghapus data ini '{{ $datas->nama_karyawan }}' ?" data-urlaction="{{ route('karyawan.delete', $datas->id) }}">Delete</button>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@if($count > 20)
			<div class="card-footer d-flex justify-content-end">
				<div>{{ $data->links() }}</div>
			</div>
		@endif
	</div>
	
	<div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">Hapus data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="confirm-text"></div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
              <a href="#" id="confirm-button" type="button" class="btn btn-outline-light">Yes</a>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
	
@endsection

@push('javascript')
	<script>
		$('#modal-danger').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget)
		  var texts = button.data('desc')
		  var urlaction = button.data('urlaction')
		  var modal = $(this)
		  document.getElementById('confirm-text').innerHTML = texts;
		  document.getElementById('confirm-button').href = urlaction;
		})
	</script>
@endpush

@push('button')
	<a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah Data</a>
@endpush