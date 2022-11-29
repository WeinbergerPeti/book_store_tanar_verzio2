<?php

use App\Models\Reservation;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->primary(["book_id", "user_id", "start"]);
            $table->foreignId("book_id")->references("book_id")->on("books");
            $table->foreignId("user_id")->references("id")->on("users");
            $table->date("start");
            $table->tinyInteger("message")->default(0);
            $table->date("message_date")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->timestamps();
        });

        Reservation::create(["book_id"=>1, "user_id" => 2, "start" => "2022.11.22", "message" =>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
