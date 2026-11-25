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
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('organization')->nullable()->after('category');
            $table->text('problem')->nullable()->after('description');
            $table->text('role')->nullable()->after('problem');
            $table->text('approach')->nullable()->after('role');
            $table->text('challenges')->nullable()->after('approach');
            $table->text('results')->nullable()->after('challenges');
            $table->boolean('is_published')->default(true)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn([
                'organization',
                'problem',
                'role',
                'approach',
                'challenges',
                'results',
                'is_published',
            ]);
        });
    }
};
