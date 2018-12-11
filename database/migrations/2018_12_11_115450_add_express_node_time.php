<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpressNodeTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresses', function (Blueprint $table) {
            $table->timestamp('node_time')->after('is_msg');
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
            $table->dropColumn('node_time');
        });
    }
}
