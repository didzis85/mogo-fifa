@extends('layouts.blank')

@push('stylesheets')

<style type="text/css">
	table {
	
		width: 400px;
		margin: 0px auto;
	}
	table td {
		border: 1px solid gray;
		min-width: 35px;
		height: 35px;
	}

	td.blank {
		background-color: silver;
	}
</style>
@endpush

@section('main_container')

	<div class="container text-center">	
		<div class="col-sm-12 mt-4"><h1>Turnīrs Nr. {{ $tournament->id }}</h1></div>
		<div class="col-sm-12 mt-3 mb-1">
			<a href="{{ route('process', ['id' => $tournament->id]) }}" class="btn btn-primary">Aiziet!</a>
		</div>
	</div>

	<div class="container text-center">	
		<div class="row">
			<div class="col-sm-6 mt-4">
				<h2>A divīzija</h2>
			
				<ol>
					@foreach( $tournament->teamsA as $xTeamA )
					<li>{{ $xTeamA->name }} (<i>Spēks: {{ $xTeamA->strength }}</i>)</li>
					@endforeach
				</ol>
			</div>
			<div class="col-sm-6 mt-4">
				<h2>B divīzija</h2>
			
				<ol>
					@foreach( $tournament->teamsB as $xTeamB )
					<li>{{ $xTeamB->name }} (<i>Spēks: {{ $xTeamB->strength }}</i>)</li>
					@endforeach
				</ol>
			</div>			
		</div>
	</div>	
	
@endsection