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
            $table->string('productName',100);
            $table->text('description');
            $table->string('reference',60)->nullable();
            $table->double('regular_price')->unsigned();
            $table->double('discount_price')->unsigned()->nullable();
            $table->boolean('status')->default(Product::AVAILABLE_PRODUCT);
            $table->integer('categorie_id')->unsigned();
            $table->timestamps();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
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
