<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('extras', function (Blueprint $table) {
            $table->string('selection_type', 10)->default('single')->after('price');
        });

        DB::table('extras')
            ->whereIn('name', ['Asiento para bebés', 'Portabicicletas', 'Conductor adicional'])
            ->update(['selection_type' => 'multiple']);
    }

    public function down(): void
    {
        Schema::table('extras', function (Blueprint $table) {
            $table->dropColumn('selection_type');
        });
    }
};
