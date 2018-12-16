<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        return Loan::with('repayments')->where('user_id',$user->id)->get();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'duration' => 'required|integer|min:1',
            'repayment_frequency' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0.001',
            'penalty_rate' => 'required|numeric|min:0.001',
            'arrangement_fee' => 'required|numeric|min:1.0',
            'total_loan' => 'required|numeric|min:100',
        ]);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $this->validator($request->all())->validate();
        return Loan::create($request->all() + ['user_id' => $user->id, 'total_paid' => 0]);
    }
}
