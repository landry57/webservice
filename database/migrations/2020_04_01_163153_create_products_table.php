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
            $table->string('code',60)->nullable();
            $table->double('price')->unsigned();
            $table->double('solde')->unsigned()->nullable();
            $table->boolean('status')->default(Product::AVAILABLE_PRODUCT);
            $table->integer('id_category_fk')->unsigned();
            $table->timestamps();
            $table->foreign('id_category_fk')->references('id')->on('categories');
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
