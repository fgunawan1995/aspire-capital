<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'duration', 'repayment_frequency', 'interest_rate', 'penalty_rate', 'arrangement_fee', 'total_loan', 'total_paid',
    ];

    public function repayments()
    {
        return $this->hasMany('App\Repayment','loan_id','id');
    }
}
