<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductsFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->engine = 'innodb';

            $table->renameColumn('product_date', 'group_date');
            $table->decimal('sell_price', 8, 2)->nullable()->default(0)->change();
            $table->decimal('cost_price', 8, 2)->nullable()->default(0)->change();

            $table->string('order_format')->nullable()->default('')->after('cost_price');
            $table->unsignedInteger('product_number')->nullable()->default(0)->after('order_format');
            $table->string('product_unit')->nullable()->default('')->after('product_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->renameColumn('group_date', 'product_date');
            $table->dropColumn('order_format');
            $table->dropColumn('product_number');
            $table->dropColumn('product_unit');
        });
    }
}
