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
            $table->increments('numero_commande')->unsigned()->unique();
            $table->integer('quantity')->unsigned();
            $table->integer('id_buyer_fk')->unsigned();
            $table->integer('id_product_fk')->unsigned();
            $table->boolean('status')->default(Transaction::UNDELIVRE);
            $table->timestamps();
          
            
            $table->foreign('id_buyer_fk')->references('id')->on('users');
            $table->foreign('id_product_fk')->references('id')->on('products');
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
