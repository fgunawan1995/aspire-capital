<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Loan;
use App\Repayment;

class RepaymentController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'loan_id' => 'required|integer|min:1',
            'total' => 'required|numeric|min:100',
        ]);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $this->validator($request->all())->validate();
        //Get loan
        $loan = Loan::findOrFail($request->loan_id);
        //Check if payment is late
        $current = Carbon::now();
        $checkDeadline = $current->diffInDays($loan->created_at);
        //Calculate minimum and maximum payment
        $total = $loan->total_loan * (1 + $loan->interest_rate) + $loan->arrangement_fee;
        $minPayment = $total / $loan->repayment_frequency;
        $maxPayment = $total - $loan->total_paid;
        //if late, add penalty fee
        if ($checkDeadline < $loan->deadline) {
            $minPayment += $loan->total_loan * (1 + $loan->penalty_rate) / $loan->repayment_frequency;
            $maxPayment += $loan->total_loan * (1 + $loan->penalty_rate) - $loan->total_paid;
        }
        //Validate total payment
        if ($user->id != $loan->user_id) {
            return response()->json([
                'message' => 'this is not your loan',
            ]);
        }
        if ($maxPayment <= 0) {
            return response()->json([
                'message' => 'loan has already been paid',
            ]);
        }
        if ($request->total < $minPayment) {
            return response()->json([
                'message' => 'minimum payment is ' . $minPayment,
            ]);
        }
        if ($request->total > $maxPayment) {
            return response()->json([
                'message' => 'maximum payment is ' . $maxPayment,
            ]);
        }
        //Save to database
        $loan->total_paid = $loan->total_paid + $request->total;
        $loan->save();
        return Repayment::create($request->all());
    }
}
