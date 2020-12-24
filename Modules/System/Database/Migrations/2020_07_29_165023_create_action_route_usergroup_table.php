<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionRouteUsergroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_route_usergroup', function (Blueprint $table) {

            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('usergroup_id');
            $table->foreign('usergroup_id')->references('id')->on('usergroups')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_menu')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_route_usergroup');
    }
}
