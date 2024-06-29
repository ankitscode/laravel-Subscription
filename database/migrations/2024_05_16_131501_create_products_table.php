<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->integer('price');
                $table->text('description');
                $table->unsignedBigInteger('media_id')->nullable();
                $table->tinyInteger('is_active')->default(1)->comment('0->no, 1->yes');
                $table->timestamps();
                $table->softDeletes();
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
