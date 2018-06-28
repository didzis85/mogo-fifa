<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::disableForeignKeyConstraints();

        Schema::create('tournaments', function (Blueprint $table) {
			$table->increments('id');			
			$table->string('status', 50)->default('new');
            $table->timestamps();
		});
		
		Schema::create('team_tournament', function (Blueprint $table) {
			
			$table->increments('id');			
			$table->integer('tournament_id')->unsigned();
			$table->integer('team_id')->unsigned();
			$table->tinyInteger('strength')->unsigned();
			$table->enum('division', ['A', 'B']);
			$table->integer('victories')->unsigned()->default(0);
			
			$table->foreign('tournament_id')->references('id')->on('tournaments');
			$table->foreign('team_id')->references('id')->on('teams');

			$table->timestamps();

		});		
		
		Schema::create('battle_tournament', function (Blueprint $table) {
			
			$table->increments('id');			
			$table->integer('tournament_id')->unsigned();
			$table->integer('battle_id')->unsigned();

			$table->foreign('tournament_id')->references('id')->on('tournaments');			
			$table->foreign('battle_id')->references('id')->on('battles');

			$table->timestamps();
			
		});

		Schema::create('battles', function (Blueprint $table) {
			
			$table->increments('id');			
			$table->integer('tournament_id')->unsigned();
			$table->integer('challenger_id')->unsigned();
			$table->integer('defender_id')->unsigned();
			$table->string('stage', 50);

			$table->foreign('tournament_id')->references('id')->on('tournaments');			
			$table->foreign('challenger_id')->references('id')->on('teams');
			$table->foreign('defender_id')->references('id')->on('teams');

			$table->timestamps();

		});
		
		Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::disableForeignKeyConstraints();
		
		Schema::dropIfExists('battle_tournament');
		Schema::dropIfExists('battles');
		Schema::dropIfExists('team_tournament');
		Schema::dropIfExists('tournaments');

		Schema::enableForeignKeyConstraints();
    }
}
