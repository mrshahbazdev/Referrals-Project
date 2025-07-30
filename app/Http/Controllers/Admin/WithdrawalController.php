<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class WithdrawalController extends Controller
{
    public function index()
    {
        $requests = WithdrawalRequest::with('user')->latest()->paginate(10);
        return view('admin.withdrawals.index', compact('requests'));
    }

    public function update(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Prevent updating non-pending requests
        if ($withdrawalRequest->status !== 'pending') {
            return back()->withErrors('This request has already been processed.');
        }

        $user = $withdrawalRequest->user;

        // If approved, check if the user has sufficient balance
        if ($request->status == 'approved') {
            if (!$user || $user->balance < $withdrawalRequest->amount) {
                return back()->withErrors('User has insufficient balance for this withdrawal.');
            }
            // Deduct balance from the user
            $user->balance -= $withdrawalRequest->amount;
            $user->save();

            // Create a transaction record for the user
            Transaction::create([
                'user_id' => $user->id,
                'amount' => -$withdrawalRequest->amount, // Store as a negative value
                'type' => 'withdrawal',
                'description' => 'Withdrawal approved by admin.',
            ]);
        }

        // Update the request status
        $withdrawalRequest->update(['status' => $request->status]);

        // Log the admin activity
        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Withdrawal Management',
            'action' => 'Withdrawal Status Updated',
            'description' => 'Admin ' . $request->status . ' withdrawal of $' . $withdrawalRequest->amount . ' for user: ' . $user->username,
        ]);

        return back()->with('success', 'Withdrawal status updated successfully.');
    }
}
