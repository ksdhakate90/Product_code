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
        Schema::create('sellers', function (Blueprint $table) {
           $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('mobile_no', 15)->unique();
    $table->string('country');
    $table->string('state');
    $table->json('skills');
    $table->string('password');
    $table->timestamp('created_at')->useCurrent();
    $table->timestamp('updated_at')->useCurrent();
        
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
};
