<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_cases', function (Blueprint $table) {
            $table->id();
            $table->string('alias');
            $table->string('contact_channel');
            $table->string('case_type');
            $table->string('priority')->default('normal');
            $table->string('status')->default('new');
            $table->text('internal_note')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('case_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_cases');
    }
};
