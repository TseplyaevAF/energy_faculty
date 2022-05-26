<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsChairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_chairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->unsignedBigInteger('chair_id');

            // IDX
            $table->index('news_id', 'news_chair_news_idx');
            $table->index('chair_id', 'news_chair_chair_idx');

            // FK
            $table->foreign('news_id', 'news_chair_news_fk')
                ->on('news')->references('id')->onDelete('cascade');
            $table->foreign('chair_id', 'news_chair_chair_fk')
                ->on('chairs')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_chairs');
    }
}
