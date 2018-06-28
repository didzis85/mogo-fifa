@extends('layouts.blank')

@push('stylesheets')

<style type="text/css">
	table tr {
		cursor: pointer;
	}
</style>
@endpush

@section('main_container')

	<div class="container text-center">	
		<div class="col-sm-12 mt-4"><h1>Notikušie turnīri</h1></div>
	</div>

	<div class="container text-center">	
		<div class="row">
			<div class="col-sm-12 mt-4">
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">Datums</th>
							<th scope="col">Uzvarētājs</th>
							<th scope="col">Uzvaras kopā</th>
							<th scope="col">Komandas spēks</th>
						</tr>
					</thead>
					<tbody>
					@foreach( $tournaments as $tournament )
						<tr onClick="window.location.href='{{ route('results', ['id' => $tournament->id]) }}'">
							<td>{{ $tournament->created_at }}</td>
							<td>{{ $tournament->winner()->name }} </td>
							<td>{{ $tournament->winner()->victories }}</td>
							<td>{{ $tournament->winner()->strength }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>		
		</div>
	</div>	
	
@endsection