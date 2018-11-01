<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMissingProductItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('missing_product_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('missing_product_id');
			$table->integer('product_id');
			$table->integer('quantity');
			$table->float('current_price', 10, 0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('missing_product_items');
	}

}
