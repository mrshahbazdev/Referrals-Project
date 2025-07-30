<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class InvestmentController extends Controller
{
    public function index()
    {
        $requests = InvestmentRequest::with('user')->latest()->paginate(10);
        return view('admin.investments.index', compact('requests'));
    }

    public function update(Request $request, InvestmentRequest $investmentRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Prevent updating non-pending requests
        if ($investmentRequest->status !== 'pending') {
            return back()->withErrors('This request has already been processed.');
        }

        // Update the request status
        $investmentRequest->update(['status' => $request->status]);

        // If approved, add balance to the user and create a transaction record
        if ($request->status == 'approved' && $investmentRequest->user) {
            $user = $investmentRequest->user;
            $user->balance += $investmentRequest->amount;
            $user->save();

            // Create a transaction record for the user
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $investmentRequest->amount,
                'type' => 'investment',
                'description' => 'Investment approved by admin.',
            ]);
        }

        // Log the admin activity
        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Investment Management',
            'action' => 'Investment Status Updated',
            'description' => 'Admin ' . $request->status . ' investment of $' . $investmentRequest->amount . ' for user: ' . $investmentRequest->user->username,
        ]);

        return back()->with('success', 'Investment status updated successfully.');
    }
}
