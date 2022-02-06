<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        DB::beginTransaction();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'type' => 'USD',
            'balance' => 1000
        ]);
        Wallet::create([
            'user_id' => $user->id,
            'type' => 'EUR',
            'balance' => 0
        ]);
        Wallet::create([
            'user_id' => $user->id,
            'type' => 'NGN',
            'balance' => 0
        ]);
        Transaction::create([
            'trans_ref' => Str::random(5),
            'sender_id' => 0,
            'receiver_id' => $user->id,
            'amount' => $wallet->balance,
            'wallet_id' => $wallet->id,
            'source_currency' => $wallet->type,
            'target_currency' => 'USD',
            'exchange_rate' => 1,
            'type' => 'Credit'
        ]);

        DB::commit();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
