<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpressesInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresses', function (Blueprint $table) {
            $table->string('contact_name')->default('')->after('product_name');
            $table->tinyInteger('is_msg')->default(0)->after('website_md5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expresses', function (Blueprint $table) {
            $table->dropColumn('contact_name');
            $table->dropColumn('is_msg');
        });
    }
}
