<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\User;
use App\Deposit;
use App\Http\Resources\UserResource;

class PaymentsController extends Controller
{

    public function deposit()
    {
        $user = auth()->user();
        $email = $user->email;

        return view('deposit', compact('email', 'user'));
    }

    public function userData()
    {
        $user = User::findOrFail(1);
        return new UserResource($user);
    }

    

    public function makeDeposit(Request $request)
    {
        $customerId = '';
        $user = auth()->user();
        $userId = $user->id;
        $userBalance = $user->balance;
        $stripeId = $user->stripe_id;
        $userObject = User::find($userId);

        if(!$stripeId) {
            $customer = Stripe::customers()->create([
                'email' => $user->email,
            ]);
            $customerId = $customer['id'];
            $card = Stripe::cards()->create($customerId, $request->stripeToken);
            $userObject->update(['stripe_id' => $customerId]);
        } else {
            $customerId = $stripeId;
        }

        try {
            
            $charge = Stripe::charges()->create([
                'amount' => $request->amount,
                'currency' => 'USD',
                'source' => $user->card_source,
                'description' => $request->email,
                'receipt_email' => $request->email,
                'customer' => $customerId
            ]);
        
            $deposit = Deposit::create([
                'user_id' => $userId,
                'transaction_id' => $charge['id'],
                'amount' => $request->amount
            ]);
            
            
            $newBalance = $userBalance + $request->amount;
            $userObject->update(['balance' => $newBalance]);

            return back()->with('success_message', 'Thank you! Your deposit was made successfully.' );
        } catch(Exception $e){
            
        }

       
    }
    

    
}
