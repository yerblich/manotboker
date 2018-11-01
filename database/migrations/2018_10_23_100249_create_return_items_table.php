<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReturnItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('return_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_items_id');
			$table->integer('product_return_id');
			$table->integer('product_id');
			$table->integer('quantity')->nullable()->default(0);
			$table->integer('currentPrice');
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
		Schema::drop('return_items');
	}

}
