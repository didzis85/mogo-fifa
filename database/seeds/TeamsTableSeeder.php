<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{		
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {		
		$teamsArray = [
			'Russia',
			'Brazil',
			'Iran',
			'Japan',
			'Mexico',
			'Belgium',
			'South Korea',
			'Saudi Arabia',
			'Germany',
			'England',
			'Spain',
			'Nigeria',
			'Costa Rica',
			'Poland',
			'Egypt',
			'Iceland',
			'Serbia',
			'Portugal',
			'France',
			'Uruguay',
			'Argentina',
			'Colombia',
			'Panama',
			'Senegal',
			'Morocco',
			'Tunisia',
			'Switzerland',
			'Croatia',
			'Sweden',
			'Denmark',
			'Australia',
			'Peru'
		];

		foreach($teamsArray as $team ) {
			DB::table('teams')->insert([
				'name' => $team
			]);
		}
    }
}
