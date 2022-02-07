<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class createUserWallets
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        DB::beginTransaction();
        $wallet = Wallet::create([
            'user_id' => $event->user->id,
            'type' => 'USD',
            'balance' => 1000
        ]);
        Wallet::create([
            'user_id' => $event->user->id,
            'type' => 'EUR',
            'balance' => 0
        ]);
        Wallet::create([
            'user_id' => $event->user->id,
            'type' => 'NGN',
            'balance' => 0
        ]);
        Transaction::create([
            'trans_ref' => Str::random(5),
            'sender_id' => 0,
            'receiver_id' => $event->user->id,
            'amount' => $wallet->balance,
            'wallet_id' => $wallet->id,
            'source_currency' => $wallet->type,
            'target_currency' => 'USD',
            'exchange_rate' => 1,
            'type' => 'Credit'
        ]);

        DB::commit();
    }
}
