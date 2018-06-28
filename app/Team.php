<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;

class Team extends Model
{
	
	public function tournament() {		
		return $this->belongsToMany(Tournament::class);
	}

	public function shortName() {
		return strtoupper( substr($this->attributes['name'],0, 2));
	}

	public function getStrengthAttribute() {
		return $this->pivot->strength;
	}

	public function getVictoriesAttribute() {
		return $this->pivot->victories;
	}	

	public function setVictoriesAttribute($value) {
		return $this->pivot->attributes['victories'] = $value;
	}
}
