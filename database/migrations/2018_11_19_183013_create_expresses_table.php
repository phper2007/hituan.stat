<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_phone')->default('');
            $table->string('product_name')->default('');
            $table->string('company_code')->default('');
            $table->string('company_name')->default('');

            $table->string('express_no')->default('');

            $table->text('express_data');

            $table->string('status')->default('');
            $table->string('website_url')->default('');
            $table->string('website_md5')->default('');

            $table->index('contact_phone');
            $table->index('product_name');
            $table->index('express_no');
            $table->index('status');
            $table->index('website_md5');
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
        Schema::dropIfExists('expresses');
    }
}
