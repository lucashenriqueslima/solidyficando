<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEducationEnumInPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the ENUM column to include 'no_school'
        DB::statement("ALTER TABLE people MODIFY COLUMN education ENUM('preschool', 'high_school', 'higher_education', 'no_school') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to the original ENUM values
        DB::statement("ALTER TABLE people MODIFY COLUMN education ENUM('preschool', 'high_school', 'higher_education') NOT NULL");
    }
}
