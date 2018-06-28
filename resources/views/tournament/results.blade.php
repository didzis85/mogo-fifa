@extends('layouts.blank')

@push('stylesheets')

<style type="text/css">
	table {		
		margin: 0px auto;
	}

	.division {
		width: 700px;
	}
	table td {
		border: 1px solid gray;
		min-width: 35px;
		height: 35px;
	}

	table.division table {
		width: 90%;
		border: none;		
	}
	table.division table td {
		border: none;
	}
	
	table.division table tr:first-child {
		border-bottom: 1px solid gray !important;
	}

	td.blank {
		background-color: silver;
	}
</style>
@endpush

@section('main_container')

	<div class="container text-center">	
		<div class="col-sm-12 mt-4"><h1>Turnīrs Nr. {{ $tournament->id }} rezultāti</h1></div>		
	</div>

	<div class="container text-center">	
		<div class="row">
			<div class="col-sm-12 mt-4">
				<h2>A divīzija</h2>
				<div class="row">
					<div class="col-sm-12">
						<table class="division">
							<tbody>
							<tr class="">
								<th>&nbsp;</th>
								@foreach( $tournament->teamsA as $xTeamA )
									<th title="{{ $xTeamA->name }}">{{ $xTeamA->shortName() }}</th>
								@endforeach	
								<th>Rez.</th>
							</tr>
							@foreach( $tournament->teamsA as $yTeamA )

								<tr class="">
									<th>{{ $yTeamA->name }}</th>
									@php ($victories = 0)
									@foreach( $tournament->teamsA as $xTeamA )																			

										@if( $xTeamA->id == $yTeamA->id )
										<td class="blank">&nbsp;</td>
										@else 
										<td title="{{ $yTeamA->name }} vs {{ $xTeamA->name }}">

										@if( $tournament->battles->where('challenger_id', $yTeamA->id)->where('defender_id', $xTeamA->id)->where('stage', 'qualification')->first()) 
											1:0 
											@php ($victories++)
 										@else 
											0:1
										@endif

										
										</td>
										@endif
										
									@endforeach	
									<td><b>{{ $victories }}</b></td>
								</tr>

							@endforeach
							</tbody>
						</table>					
					</div>
				</div>
			</div>
			<div class="col-sm-12 mt-4">
				<h2>B divīzija</h2>
				<div class="row">
					<div class="col-sm-12">
						<table class="division">
							<tbody>
							<tr class="">
								<th>&nbsp;</th>
								@foreach( $tournament->teamsB as $xTeamB )
									<th title="{{ $xTeamB->name }}">{{ $xTeamB->shortName() }}</th>
								@endforeach	
								<th>Rez.</th>
							</tr>
							@foreach( $tournament->teamsB as $yTeamB )

								<tr class="">
									<th>{{ $yTeamB->name }}</th>
									@php ($victories = 0)
									@foreach( $tournament->teamsB as $xTeamB )																			

										@if( $xTeamB->id == $yTeamB->id )
										<td class="blank">&nbsp;</td>
										@else 
										<td title="{{ $yTeamB->name }} vs {{ $xTeamB->name }}">

										@if( $tournament->battles->where('challenger_id', $yTeamB->id)->where('defender_id', $xTeamB->id)->where('stage', 'qualification')->first()) 
											1:0 
											@php ($victories++)											
 										@else 
											0:1
										@endif

										
										</td>
										@endif
										
									@endforeach	
									<td><b>{{ $victories }}</b></td>
								</tr>

							@endforeach
							</tbody>
						</table>					
					</div>
				</div>
			</div>


				
			<div class="col-sm-12 mt-4">
				<h2>Playoffs</h2>
				<div class="row">
					<div class="col-sm-12">
						<table  class="division">
						<thead>
							<tr>
								<th>Quarter-finals</th>
								<th>Semi-finals</th>
								<th>Finals</th>
								<th>Results</th>
							</tr>
						</thead>
						<tbody>							
							<tr>
								<td style="width: 25%; vertical-align: top;">

									@foreach( $tournament->battles()->where('stage', 'playoffs')->get() as $battle )									
										<table class="mb-2">
											<tr>
												<td>
												{{ $battle->winner->name }}												
												</td>
												<td>1</td>
											</tr>
											<tr>
												<td>{{ $battle->looser->name }}</td>
												<td>0</td>
											</tr>

										</table>
									@endforeach


								</td>


								<td style="width: 25%; vertical-align: top;">
									@foreach( $tournament->battles()->where('stage', 'semi_finals')->get() as $battle )									
										<table class="mb-2">
											<tr>
												<td>
												{{ $battle->winner->name }}											
												</td>
												<td>1:0</td>
											</tr>
											<tr>
												<td>{{ $battle->looser->name }}</td>
												<td>&nbsp;</td>
											</tr>

										</table>
									@endforeach								
								</td>

								<td style="width: 25%; vertical-align: top;">
								
									@foreach( $tournament->battles()->where('stage', 'finals')->get() as $battle )									
										<table class="mb-2">
											<tr>
												<td>
												{{ $battle->winner->name }}											
												</td>
												<td>1:0</td>
											</tr>
											<tr>
												<td>{{ $battle->looser->name }}</td>
												<td>&nbsp;</td>
											</tr>

										</table>
									@endforeach

								</td>

								<td style="width: 25%; vertical-align: top;" class="text-left">
									<ol>
										@foreach( $tournament->teams()->orderBy('victories', 'DESC')->get() as $team )
											<li>{{ $team->name }} ({{ $team->victories }})</li>
										@endforeach										
									</ol>
								</td>
							</tr>
						</tbody>
						</table>					
					</div>
				</div>
			</div>
		</div>
	</div>	
	
@endsection