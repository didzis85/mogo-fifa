<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;

class Battle extends Model
{

	public function tournament() {
		return $this->belongsToMany(Tournament::class)->using(Battle::class);
	}

	public function winner() {
		return $this->hasOne(Team::class, 'id', 'challenger_id');
	}

	public function looser() {
		return $this->hasOne(Team::class, 'id', 'defender_id');
	}

}
