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
            $table->integer('customer_id')->nullable()->default(null);
            $table->string('status');
            $table->string('surname');
            $table->string('name');
            $table->string('email');
            $table->string('tel')->nullable()->default(null);
            $table->float('subtotal');
            $table->float('subtotal_tax');
            $table->float('shipping');
            $table->float('shipping_tax');
            $table->float('total');
            $table->float('total_tax');
            $table->float('final');
            $table->longText('json');
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
        Schema::dropIfExists('customers');
    }
}
