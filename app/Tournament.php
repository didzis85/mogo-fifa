<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Battle;
use DB;

class Tournament extends Model
{
	//
	protected $fillable = [
        'status'
	];

	public $victories;
	
	public function splitDivisions() {
		
		$teams 		= Team::all()->toArray();		
		$divisionA 	= [];
		$divisionB 	= [];
		$_teamCount = count($teams);
		
		$aParticipantCount = ceil($_teamCount/2);
		$bParticipantCount = $_teamCount - $aParticipantCount;
	
		$_aParticipants = array_rand($teams, $aParticipantCount);
		
		foreach( $_aParticipants as $aIndex ){
			$aParticipants[] = $teams[$aIndex];
			unset($teams[$aIndex]);
		}

		$teams = array_values($teams);
		$bParticipants = $teams;

		return [
			'A' => $aParticipants,
			'B' => $bParticipants
		];

	}

	public function teams() {
		return $this->belongsToMany(Team::class)->withPivot('division', 'strength', 'victories');
	}

	public function teamsA() {
		return $this->belongsToMany(Team::class)->where('division','A')->withPivot('division', 'strength', 'victories');
	}

	public function teamsB() {
		return $this->belongsToMany(Team::class)->where('division','B')->withPivot('division', 'strength', 'victories');
	}

	/*
	 * Random skaitļu minēšana no 1 - 100. Komanda, kurai ir lielāks "strength" (1 - 100) ir lielāka iespēja, ka random
	 * numurs iekritīs šajā diapazonā attiecīgi - uzvara.
	 */
	public function processBattle( Team $team1, Team $team2, $stage = 'qualification' ) {
		
		$x = 5;
		$team1Wins = 0;
		$team2Wins = 0;
		
		while(--$x) {
			$gameNumber = rand(1, 100);

			if( $gameNumber < $team1->strength )
				$team1Wins++;

			if( $gameNumber < $team2->strength )
				$team2Wins++;
		}

		if( $team1Wins == $team2Wins && rand(1,2) == 1) {
			$team1Wins++;			
		} else {
			$team2Wins++;
		}

		if( $team1Wins > $team2Wins ) {
			$winner = $team1;
			$looser = $team2;
		} else {
			$winner = $team2;
			$looser = $team1;
		}

		$winner->victories++;

		$battle = new Battle;
		$battle->tournament_id 	= $this->id;
		$battle->challenger_id 	= $winner->id;
		$battle->defender_id	= $looser->id;
		$battle->stage 			= $stage;
		$battle->save();

		return [
			'winner' => $winner,
			'looser' => $looser,
			'battle_id' => $battle->id
		];

	}	

	public function battles() {
		return $this->belongsToMany(Battle::class);
	}

	public function winner() {


		$ret = $this->belongsToMany(Team::class)->orderBy('victories', 'DESC')->withPivot('division', 'strength', 'victories')->first();

		return $ret;
	}
}
