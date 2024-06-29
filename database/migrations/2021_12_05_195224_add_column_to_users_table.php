<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->after('remember_token')->nullable();
            $table->string('phone')->after('full_name')->nullable();
            $table->date('birthdate')->after('phone')->nullable();
            $table->unsignedBigInteger('gender_type')->nullable()->after('birthdate')->comment('lockup');
            $table->unsignedBigInteger('city_type')->nullable()->after('gender_type')->comment('lockup');
            $table->unsignedBigInteger('country_type')->nullable()->after('city_type')->comment('lockup');
            $table->text('address')->nullable()->after('country_type')->comment('lockup');
            $table->tinyInteger('is_active')->default(1)->comment('0->de-active, 1->active')->after('address')->nullable();
            $table->text('media_id')->after('is_active')->nullable();

            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->text('uuid')->after('updated_by')->nullable();

            // foreign-key-reference-on-table

            $table->foreign('gender_type')->references('id')->on('lockups');
            $table->foreign('city_type')->references('id')->on('lockups');
            $table->foreign('country_type')->references('id')->on('lockups');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
