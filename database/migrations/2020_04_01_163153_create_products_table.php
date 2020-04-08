<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Product;

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
            $table->increments('id');
            $table->string('name',60);
            $table->string('description',1000);
            $table->bigInteger('code')->unsigned()->nullable();
            $table->double('price')->unsigned();
            $table->double('solde')->unsigned();
            $table->boolean('status')->default(Product::AVAILABLE_PRODUCT);
            $table->integer('saller_id')->unsigned();
            $table->integer('sub_category_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('saller_id')->references('id')->on('users');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
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
