<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plzs', function (Blueprint $table) {
            $table->id();
            $table->string('plz', 10);
            $table->string('ort', 200);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('berzirk', 200);
            $table->timestamps();
            $table->unsignedBigInteger('kantone_id');
            $table->foreign('kantone_id')
                ->references('id')
                ->on('kantones')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plzs');
    }
};
