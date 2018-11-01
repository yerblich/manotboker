<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('supplier_id')->index('supplier_id');
			$table->string('name', 191)->unique('name');
			$table->timestamps();
			$table->integer('weight')->nullable();
			$table->float('supplier_price', 10, 0)->default(0);
			$table->integer('active')->default(1);
			$table->integer('type')->default(0);
			$table->string('image')->default('noimage.jpg');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
