<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            /*$table
                ->foreignId("role_id")
                ->index()
                ->constrained()
                ->unsigned()
                ->nullable();*/ //komt uit 1-op-veel relatie
            $table->integer("is_active")->default(0);
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("photo_id")->default("");
            $table
                ->string("password")
                ->nullable()
                ->default("NULL");
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); //deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("users");
    }
};
