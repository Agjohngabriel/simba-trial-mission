<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function dashboard(){
        $wallet = Wallet::where('user_id', Auth::user()->id)->get();
        $trans = Transaction::where('receiver_id', Auth()->user()->id)->orWhere('sender_id', Auth()->user()->id)->orderBy('id', 'DESC')->get();
        return view('dashboard')->with(['trans'=> $trans, 'wallet' => $wallet]);
    }

    public function inittransfer(){
        $wallets = Wallet::where('user_id', Auth::user()->id)->get();
        $users = User::where('id', '!=', Auth::user()->id)->get();
        return view('trans')->with([
            'wallets' => $wallets,
            'users' => $users
        ]);

    }
    public  function transfer(Request $request){
            $rate = Http::get('https://api.exchangerate.host/latest?', [
                'base' => $request->source,
                'symbols' => $request->target,
            ])['rates'][$request->target];
            $wallet = Auth::user()->wallet()->where('type' , $request->source)->first();
            $converted = $request->amount * $rate;
            $receiver = User::where('id', $request->receiver)->first();
            $receiversWallet =  $receiver->wallet()->where('type', $request->target)->first();
            DB::beginTransaction();

        Transaction::create([
            'trans_ref' => 'wise'.Str::random(5),
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver,
            'amount' => $converted,
            'wallet_id' => $wallet->id,
            'source_currency' => $request->source,
            'target_currency' => $request->target,
            'exchange_rate' => $rate,
        ]);

        $wallet->balance = $wallet->balance - $request->amount;
        $wallet->save();

        $receiversWallet->balance = $receiversWallet->balance + $converted;
        $receiversWallet->save();

        DB::commit();

        return redirect()->route('dashboard')->with('message', 'Transaction was successful');

    }
}
