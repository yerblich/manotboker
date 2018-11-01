<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 192);
			$table->string('email', 191)->unique('email');
			$table->timestamps();
			$table->float('debt', 10, 0)->nullable()->default(0);
			$table->float('credit', 10, 0)->nullable()->default(0);
			$table->integer('route')->nullable();
			$table->string('address')->nullable();
			$table->string('city')->nullable();
			$table->string('number')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
