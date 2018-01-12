<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sends', function (Blueprint $table) {
            $table->increments('id');
            $table->float('min');
            $table->float('max');
            $table->string('type');
            $table->float('l1')->nullable();
            $table->float('l2')->nullable();
            $table->float('l3')->nullable();
            $table->float('l4')->nullable();
            $table->float('e1')->nullable();
            $table->float('e2')->nullable();
            $table->float('e3')->nullable();
            $table->float('e4')->nullable();
            $table->float('n1')->nullable();
            $table->float('n2')->nullable();
            $table->float('n3')->nullable();
            $table->float('n4')->nullable();
            $table->float('n5')->nullable();
            $table->float('n6')->nullable();
            $table->float('i1')->nullable();
            $table->float('i2')->nullable();
            $table->float('i3')->nullable();
            $table->float('i4')->nullable();
            $table->float('i5')->nullable();
            $table->float('i6')->nullable();
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
        Schema::dropIfExists('sends');
    }
}
