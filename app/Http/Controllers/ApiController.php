<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddWalletAmountRequest;
use App\Http\Requests\BuyCookieRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserProfileCollection;
use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseWithHttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    use ResponseWithHttpRequest;
    public function login(LoginRequest $request)
    {
        try {
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                DB::beginTransaction();
                $access_token       = auth()->user()->createToken(auth()->user()->name)->accessToken;
                DB::commit();
                return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token, 'profile_data' => new UserProfileCollection(auth()->user())]);
            } else {
                return $this->sendFailed('YOUR PASSWORD IS INVALID. PLEASE TRY AGAIN', 200);
            }
        } catch (\Throwable $e) {
            Log::info('Login Cookie Error Start ======================');
            Log::info($e->getMessage() . ' on line ' . $e->getLine());
            Log::info('Login Cookie Error End ======================');
            DB::rollback();
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    function profile()
    {
        try {
            return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['profile_data' => new UserProfileCollection(auth()->user())]);
        } catch (\Throwable $e) {
            Log::info('Login Cookie Error Start ======================');
            Log::info($e->getMessage() . ' on line ' . $e->getLine());
            Log::info('Login Cookie Error End ======================');
            DB::rollback();
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    function addAmountInWallet(AddWalletAmountRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_data = auth()->user();
            $user_data->increment('wallet', $request->amount);
            DB::commit();
            return $this->sendSuccess('ADD WALLET AMOUNT SUCCESSFULLY', ['wallet' => round(auth()->user()->wallet, 2)]);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::info('Add Amount In Wallet Error Start ======================');
            Log::info($e->getMessage() . ' on line ' . $e->getLine());
            Log::info('Add Amount In Wallet Error End ======================');
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    function buyCookie(BuyCookieRequest $request)
    {
        try {
            $user_data = auth()->user();
            $get_cookie_price = Product::value('amount');
            $total_cookie_amount = $request->quantity * $get_cookie_price;
            if ($total_cookie_amount > $user_data->wallet) {
                return $this->sendFailed('Insufficient Balance', 200);
            }
            DB::beginTransaction();
            $user_data->decrement('wallet', $total_cookie_amount);
            DB::commit();
            return $this->sendSuccess('COOKIE BUY SUCCESSFULLY');
        } catch (\Throwable $e) {
            DB::rollback();
            Log::info('Buy Cookie Error Start ======================');
            Log::info($e->getMessage() . ' on line ' . $e->getLine());
            Log::info('Buy Cookie Error End ======================');
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }
}
