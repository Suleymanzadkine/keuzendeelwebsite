<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keuzedelen', function (Blueprint $table) {
            $table->timestamp('low_notified_at')->nullable()->after('allow_multiple');
        });
    }

    public function down()
    {
        Schema::table('keuzedelen', function (Blueprint $table) {
            $table->dropColumn('low_notified_at');
        });
    }
};