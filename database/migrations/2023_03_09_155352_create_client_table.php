<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('сlient', function (Blueprint $table) {
            $table->string('ref', 32);
            $table->integer('idClient');
            $table->timestamp('dateCurr');
            $table->string('phone', 45);
            $table->string('mail', 45)->nullable();
            $table->string('address', 45)->nullable();
            $table->decimal('monthSalary', 15, 2)->nullable();
            $table->char('currSalary', 3)->nullable();
            $table->string('decision', 45)->nullable();
            $table->decimal('limitItog', 15, 2)->nullable();
            $table->decimal('requestLimit', 15, 2)->nullable();
            $table->primary('ref');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('сlient');
    }
};
