<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->after('id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('duration'); //in days, e.g : if duration = 180 and repayment_frequency = 6, it will be paid in 30 days increment (but payment can be anytime)
            $table->integer('repayment_frequency'); //how many times will this loan repaid (i will use this for calculating minimum repayment, min repayment = total_loan/repayment_frequency)
            $table->float('interest_rate', 8, 2);
            $table->float('penalty_rate', 8, 2); //if payment is late, this will be added
            $table->float('arrangement_fee', 16, 2);
            $table->float('total_loan', 16, 2);
            $table->float('total_paid', 16, 2); //how much it has been paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
