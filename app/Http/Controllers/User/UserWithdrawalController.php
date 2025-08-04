<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserWithdrawalController extends Controller
{
    public function create()
    {
        $user = Auth::user()->load('level');
        $isEligible = $user->isEligibleForWithdrawal();

        // Check karein ke user ne is hafte pehle hi withdrawal to nahi kiya
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

        $lastWithdrawal = WithdrawalRequest::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->first();

        return view('withdrawals.create', compact('user', 'isEligible', 'lastWithdrawal'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEligibleForWithdrawal()) {
            return back()->with('error', 'Please complete your profile and KYC to enable withdrawals.');
        }

        $startOfWeek = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
        $hasWithdrawnThisWeek = \App\Models\WithdrawalRequest::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($hasWithdrawnThisWeek) {
            return back()->with('error', 'You can only make one withdrawal request per week.');
        }

        // **NAYA BEHTAR VALIDATION LOGIC**
        $levelLimit = $user->level->weekly_withdrawal_limit ?? 0;
        $userBalance = $user->balance;

        // User sirf utna hi withdraw kar sakta hai jo uske balance aur level limit, dono se kam ho
        $maxAllowed = min($levelLimit, $userBalance);

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $maxAllowed,
            'wallet_address' => 'required|string|max:255',
        ], [
            'amount.max' => 'The withdrawal amount exceeds your available balance or weekly limit.'
        ]);

        // -- Baqi code waisa hi rahega --

        $user->balance -= $request->amount;
        $user->save();

        \App\Models\WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'wallet_address' => $request->wallet_address,
            'status' => 'pending',
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'amount' => -$request->amount,
            'type' => 'withdrawal',
            'description' => 'Withdrawal request submitted (pending approval).',
        ]);

        return back()->with('success', 'Your withdrawal request has been submitted and is pending approval.');
    }
}
