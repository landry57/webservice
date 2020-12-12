<?php

use App\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Transaction;
class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->unsigned();
            $table->boolean("confirmed")->default(0);
            $table->double('price');
            $table->integer('user_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('secteur_id')->unsigned()->nullable();
            $table->boolean('status')->default(Transaction::UNDELIVRE);
            $table->timestamps();
          
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('secteur_id')->references('id')->on('secteurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
