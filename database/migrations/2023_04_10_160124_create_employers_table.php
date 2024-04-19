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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->string('name', 80)->default('');
            $table->string('logo_url', 200)->default('');
            $table->string('address', 200)->default('');
            $table->string('email', 80)->default('');
            $table->string('phone', 60)->default('');
            $table->string('fax', 60)->default('');
            $table->string('size', 60)->default('');
            $table->string('c_p_name', 80)->default('');
            $table->string('c_p_surname', 80)->default('');
            $table->string('c_p_email', 80)->default('');
            $table->string('c_p_gender', 60)->default('');
            $table->string('c_p_phone', 80)->default('');
            $table->string('c_p_fax', 80)->default('');
            $table->string('team_email', 80)->default('');
            $table->text('description')->nullable();
            $table->string('page_url')->nullable();
            $table->string('ort', 200)->nullable();
            $table->string('holidays')->nullable();
            $table->text('maternity_benefits')->nullable();
            $table->text('benefits')->nullable();
            $table->boolean("is_active")->default(false);
            $table->timestamps();

            //foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kantone_id')->nullable();
            $table->unsignedBigInteger('plz_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('kantone_id')
                ->references('id')
                ->on('kantones')
                ->onDelete('cascade');
            $table->foreign('plz_id')
                ->references('id')
                ->on('plzs')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('employer_categories')
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
        Schema::dropIfExists('employers');
    }
};
