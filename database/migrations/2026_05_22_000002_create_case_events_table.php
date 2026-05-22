<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intake_case_id')->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['intake_case_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_events');
    }
};
