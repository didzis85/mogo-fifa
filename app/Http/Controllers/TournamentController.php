<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournament;
use App\Team;
use DB, Log;

class TournamentController extends Controller
{
	
	public function init()
    {
		try {
			
			$tournament = new Tournament();
			$tournament->status = 'started';
			$tournament->save();

			if(!count($tournament->teams)) {

				$divisions = $tournament->splitDivisions();

				for($i=0;$i<count($divisions['A']);$i++) {
					$tournament->teams()->attach($divisions['A'][$i]['id'], ['division' => 'A', 'strength' => rand(1, 100)]);
				}

				for($i=0;$i<count($divisions['B']);$i++) {
					$tournament->teams()->attach($divisions['B'][$i]['id'], ['division' => 'B', 'strength' => rand(1, 100)]);
				}

			}			

			return view('tournament.init', compact('tournament', 'teamsA', 'teamsB'));
			
		} catch (\Exception $e) {
		
			dd($e->getMessage());
					
		}		
	}
	
	public function history() {		
		try {
			
			$tournaments = Tournament::where('status', 'finished')->orderBy('created_at', 'DESC')->get();
			return view('tournament.history', compact('tournaments'));

		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}
	
	public function results($id) {		
		try {
			
			$tournament = Tournament::find($id);
			return view('tournament.results', compact('tournament'));

		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}	

	public function process($id) {
		try {
			
			$tournament = Tournament::find($id);
			$teamsA 	= $tournament->teamsA;
			$teamsB 	= $tournament->teamsB;
			$divisions 	= [$teamsA, $teamsB];
			$battles 	= [];
			
			// cīņas divīziju ietvaros.
			foreach( $divisions as $teams ) {

				// izspēlē katrs ar katru
				foreach( $teams as $xTeam ) {
					foreach( $teams as $yTeam ) {

						$battle = [$xTeam->id, $yTeam->id];

						// Komanda A nespēlē ar komandu A && komandu spēle A vs B ir tas pats, kas B vs A
						if( $xTeam->id != $yTeam->id && !in_array(array_reverse($battle), $battles) ) {

							$battles[] = $battle;
							$results = $tournament->processBattle($xTeam, $yTeam, 'qualification');

							$tournament->battles()->attach($results['battle_id']);
							$tournament->teams()->updateExistingPivot($results['winner']->id, [ 'victories' => $results['winner']->victories ]);

						}

					}		
				}
			}

			// četri labākie no A divīzijas
			$divisionWinnersA = $tournament->teamsA()->
												orderBy('victories', 'DESC')->
												orderBy('strength', 'DESC')->
												take(4)->
												get();
												
			// četri labākie no B divīzijas, kājām gaisā (stiprākais vs vājākais)
			$divisionWinnersB = $tournament->teamsB()->
												orderBy('victories', 'DESC')->
												orderBy('strength', 'DESC')->
												take(4)->
												get()->reverse();


			Log::info("------[ Playoffs ] -------");
			// playoffs. ugly.
			$semiFinals = collect([]);
			for($i=0;$i<$divisionWinnersA->count(); $i++) {				

				$participantA 	= $divisionWinnersA->values()->get($i);
				$participantB 	= $divisionWinnersB->values()->get($i);				

				$results 		= $tournament->processBattle( $participantA, $participantB, 'playoffs' );
				$semiFinals->push($results['winner']);

				Log::info($participantA->name." vs ".$participantB->name.' winner: '.$results['winner']->name.' ('.$results['winner']->victories.')');

				$tournament->battles()->attach($results['battle_id']);
				$tournament->teams()->updateExistingPivot($results['winner']->id, [ 'victories' => $results['winner']->victories ]);

			}
			
			Log::info("------[ Pusfināls ] -------".chr(10));
			$finals = collect([]);
			
			$participantA 	= $semiFinals->sortBy('victories')->slice(0, 1)->values()->get(0);
			$participantB 	= $semiFinals->sortBy('victories')->slice(1, 1)->values()->get(0);
			$participantC 	= $semiFinals->sortBy('victories')->slice(2, 1)->values()->get(0);
			$participantD 	= $semiFinals->sortBy('victories')->slice(3, 1)->values()->get(0);			


			// pirmais pāris izspēlē pusfinālu
			$results 		= $tournament->processBattle( $participantA, $participantD, 'semi_finals' );
			Log::info($participantA->name." vs ".$participantD->name.' winner: '.$results['winner']->name.' ('.$results['winner']->victories.')'.chr(10).chr(10));

			$tournament->battles()->attach($results['battle_id']);
			$tournament->teams()->updateExistingPivot($results['winner']->id, [ 'victories' => $results['winner']->victories ]);
			$finals->push($results['winner']);
		
			// otrais pāris izspēlē pusfinālu
			$results 		= $tournament->processBattle( $participantB, $participantC, 'semi_finals' );
			
			Log::info($participantB->name." vs ".$participantC->name.' winner: '.$results['winner']->name.' ('.$results['winner']->victories.')'.chr(10).chr(10));

			$tournament->battles()->attach($results['battle_id']);
			$tournament->teams()->updateExistingPivot($results['winner']->id, [ 'victories' => $results['winner']->victories ]);
			$finals->push($results['winner']);
						
			// Grand finale!
			$participantA 	= $finals->values()->get(0);
			$participantB 	= $finals->values()->get(1);
			
			Log::info("------[ Grand finalee ] -------".chr(10));
			$results 		= $tournament->processBattle( $participantA, $participantB, 'finals' );
			
			Log::info($participantA->name." vs ".$participantB->name.' winner: '.$results['winner']->name.' ('.$results['winner']->victories.')'.chr(10).chr(10));
			$tournament->battles()->attach($results['battle_id']);
			$tournament->teams()->updateExistingPivot($results['winner']->id, [ 'victories' => $results['winner']->victories ]);
			
			$tournament->status = 'finished';
			$tournament->save();
			
			return redirect()->route('results', [ 'id' => $tournament->id]);

		} catch (\Exception $e) {

			dd($e);
			
		}

	}

}
