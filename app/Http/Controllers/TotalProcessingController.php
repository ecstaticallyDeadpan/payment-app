<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TotalProcessingController extends Controller
{
    /*
    * Takes amount value from user form and returns checkout id
    */
    public function generateCheckout(Request $request)
    {
        $url = "https://eu-test.oppwa.com/v1/checkouts";
        $data = "entityId=" . config('paymentGateway.tp_entity_id') .
            "&amount=" . $request->input('amount') .
            "&currency=GBP" .
            "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . config('paymentGateway.tp_access_token')
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        // Save transaction
        $transaction_info = json_decode($responseData);

        Transaction::create([
            'user_id' => auth()->user()->id,
            'checkout_id' => $transaction_info->id,
            'reference' => strip_tags($request->input('reference')),
            'amount' => $request->input('amount'),
            'status' => 'incomplete',
        ]);

        return $responseData;
    }
    /*
    *  Sends request to TP to get status of payment
    */
    public function checkStatus(Request $request)
    {
        $id = $request->input('checkout_id');
        $url = "https://eu-test.oppwa.com/v1/checkouts/";
        $url .= $id . "/payment";
        $url .= "?entityId=" . config('paymentGateway.tp_entity_id');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . config('paymentGateway.tp_access_token')
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        // Update transaction
        $transaction_info = json_decode($responseData);
        if($transaction_info->result->code == '000.100.110'){
            $transaction = Transaction::where('checkout_id', $request->input('checkout_id'))->first();
            $transaction->status = 'complete';
            $transaction->save();
        }

        return $responseData;
    }
}
