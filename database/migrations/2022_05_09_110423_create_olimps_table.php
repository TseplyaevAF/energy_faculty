<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlimpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('olimps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('olimp_type_id');
            $table->unsignedBigInteger('news_id');
            $table->unsignedInteger('year');
            $table->timestamps();

            // IDX
            $table->index('olimp_type_id', 'olimp_olimp_type_idx');
            $table->index('news_id', 'olimp_news_idx');

            // FK
            $table->foreign('olimp_type_id', 'olimp_olimp_type_fk')->on('olimp_types')->references('id');
            $table->foreign('news_id', 'olimp_news_fk')->on('news')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('olimps');
    }
}
