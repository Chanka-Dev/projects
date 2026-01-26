<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('recordatorios', function (Blueprint $table) {
        $table->string('mensaje')->after('cita_id');
    });
}

public function down()
{
    Schema::table('recordatorios', function (Blueprint $table) {
        $table->dropColumn('mensaje');
    });
}
};
