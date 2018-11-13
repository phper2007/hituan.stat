<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->date('product_date');
            $table->unsignedInteger('address_id');
            $table->unsignedInteger('product_id');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('address');
            $table->string('contact_name');
            $table->string('contact_phone');

            $table->string('product_name');
            $table->unsignedInteger('sell_count');
            $table->decimal('sell_price', 8, 2);
            $table->decimal('cost_price', 8, 2);
            $table->string('detail_name1');
            $table->string('detail_name2');
            $table->string('detail_name3');
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
        Schema::dropIfExists('orders');
    }
}
