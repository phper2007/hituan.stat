<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('product_date', 'group_date');
            $table->decimal('sell_price', 8, 2)->nullable()->default(0)->change();
            $table->decimal('cost_price', 8, 2)->nullable()->default(0)->change();

            $table->string('detail_name1')->nullable()->default('')->change();
            $table->string('detail_name2')->nullable()->default('')->change();
            $table->string('detail_name3')->nullable()->default('')->change();

            $table->string('detail_name4')->nullable()->default('')->after('detail_name3');
            $table->string('detail_name5')->nullable()->default('')->after('detail_name4');
            $table->unsignedInteger('freight')->nullable()->default(0)->after('cost_price');


            $table->integer('detail_count1')->nullable()->default(0)->after('detail_name1');
            $table->integer('detail_count2')->nullable()->default(0)->after('detail_name2');
            $table->integer('detail_count3')->nullable()->default(0)->after('detail_name3');
            $table->integer('detail_count4')->nullable()->default(0)->after('detail_name4');
            $table->integer('detail_count5')->nullable()->default(0)->after('detail_name5');

            //快递相当
            $table->string('express_no')->default('')->after('detail_name5');
            $table->string('express_name')->default('')->after('express_no');
            $table->integer('express_signed')->default(0)->comment('快递状态：0默认，100签收。')->after('express_name');
            $table->tinyInteger('is_msg')->default(0)->comment('是否有新消息：0默认，1提醒。')->after('express_signed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('group_date', 'product_date');
            $table->dropColumn('freight');
            $table->dropColumn('detail_name4');
            $table->dropColumn('detail_name5');

            $table->dropColumn('express_no');
            $table->dropColumn('express_name');
            $table->dropColumn('express_signed');
            $table->dropColumn('is_msg');
        });
    }
}