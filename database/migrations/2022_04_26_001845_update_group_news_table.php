<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGroupNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_news', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->unsignedBigInteger('user_id');
            $table->dropSoftDeletes();
            // IDX
            $table->index('user_id', 'group_news_user_idx');

            // FK
            $table->foreign('user_id', 'group_news_user_fk')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_news', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->string('title');
            $table->softDeletes();
        });
    }
}
