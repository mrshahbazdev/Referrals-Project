@extends('layouts.frontend')

@section('title', 'Make a Withdrawal')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <i class="ph-bold ph-arrow-circle-up-right text-5xl text-yellow-400"></i>
            <h2 class="text-2xl font-bold text-white mt-4">Request a Withdrawal</h2>
            <p class="text-gray-400">Your current balance: <span class="font-bold text-white">${{ number_format($user->balance, 2) }}</span></p>
        </div>

        @if (session('success'))
            <div class="bg-green-500/10 text-green-300 p-4 rounded-lg text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center">{{ session('error') }}</div>
        @endif

        @if($isEligible)
            @if($lastWithdrawal)
                <div class="bg-blue-500/10 text-blue-300 p-4 rounded-lg text-center">
                    You have already made a withdrawal this week. You can make another request after {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}.
                </div>
            @else
                <form method="POST" action="{{ route('withdraw.store') }}" class="space-y-4 bg-[#1E1F2B] p-6 rounded-lg">
                    @csrf
                    <div>
                        <label for="amount" class="text-sm font-medium text-gray-400">Amount (USD) - Max: ${{ number_format($user->level->weekly_withdrawal_limit, 2) }}</label>
                        <input type="number" step="0.01" id="amount" name="amount" class="mt-1 w-full bg-[#334155] rounded-lg p-3" required>
                        @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="wallet_address" class="text-sm font-medium text-gray-400">Your Wallet Address</label>
                        <input type="text" id="wallet_address" name="wallet_address" class="mt-1 w-full bg-[#334155] rounded-lg p-3" required>
                        @error('wallet_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg">Submit Request</button>
                </form>
            @endif
        @else
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center space-y-4">
                <p class="font-bold">Withdrawals are disabled.</p>
                <p class="text-sm">To enable withdrawals, please complete your profile information and get your KYC verified.</p>
                <div class="flex gap-4 justify-center mt-2">
                    <a href="{{ route('profile.edit') }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg text-sm">Complete Profile</a>
                    <a href="{{ route('kyc.create') }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg text-sm">Verify KYC</a>
                </div>
            </div>
        @endif
    </div>
@endsection
