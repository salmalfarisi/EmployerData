<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_absens', function (Blueprint $table) {
            $table->id();
			$table->foreignId('userId')->constrained('users');
			$table->timestamp('absenmasuk')->nullable();
			$table->timestamp('absenkeluar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_absens');
    }
}
