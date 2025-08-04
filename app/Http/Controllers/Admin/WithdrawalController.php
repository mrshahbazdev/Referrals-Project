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

    public function update(Request $request, \App\Models\WithdrawalRequest $withdrawalRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($withdrawalRequest->status !== 'pending') {
            return back()->withErrors('This request has already been processed.');
        }

        $user = $withdrawalRequest->user;
        $newStatus = $request->status;

        // **Case 1: Agar request REJECT ho jaye**
        if ($newStatus == 'rejected') {
            // Kaati hui raqam user ke balance mein wapas daal dein
            $user->balance += $withdrawalRequest->amount;
            $user->save();

            // User ke liye "Refund" ka transaction record banayein
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'amount' => $withdrawalRequest->amount, // Raqam ko positive mein save karein
                'type' => 'refund', // Hum is nayi type ko istemal kar sakte hain
                'description' => 'Withdrawal request rejected. Amount refunded.',
            ]);
        }

        // **Case 2: Agar request APPROVE ho jaye**
        // Humein kuch karne ki zaroorat nahi, kyunke balance pehle hi cut chuka hai.

        // Request ka status update karein
        $withdrawalRequest->update(['status' => $newStatus]);

        // Admin ki activity log karein
        \App\Models\AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Withdrawal Management',
            'action' => 'Withdrawal Status Updated',
            'description' => 'Admin ' . $newStatus . ' withdrawal of $' . $withdrawalRequest->amount . ' for user: ' . $user->username,
        ]);

        return back()->with('success', 'Withdrawal status updated successfully.');
    }
}
