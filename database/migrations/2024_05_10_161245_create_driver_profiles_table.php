<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('real_name', 255)->default('');
            $table->string('vehicle_type', 255)->default('');
            $table->string('license_plate_number', 20)->default('');
            $table->integer('maximum_passengers')->default(0);
            $table->text('special_vehicle_info')->nullable();
            $table->boolean('is_driver')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_profiles');
    }
}