<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAddressesMsgCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->integer('msg_count')->default(0)->after('contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('msg_count');
        });
    }
}
