<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidesTable extends Migration
{
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('destination', 1023);
            $table->timestamp('required_arrival_time');
            $table->integer('passenger_number_from_owner');
            $table->integer('passenger_number_in_total');
            $table->string('ride_status', 255)->default('open');
            $table->string('requested_vehicle_type', 255)->nullable();
            $table->text('special_request')->nullable();
            $table->boolean('can_be_shared')->default(false);
            $table->json('sharer_id_and_passenger_number_pair')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rides');
    }
}