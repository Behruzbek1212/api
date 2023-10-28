<?php

$user = \App\Models\User::where('id', $transaction->transactionable_id)->first();
$user->balance += $transaction->amount;
$user->save();
Http::withoutVerifying()->post("https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage", [
    'chat_id' => '-1001821241273',
    'text' => $user,
]);
// \App\Models\User::where('id', $transaction->transactionable_id)
//     ->update([
//         'balance' => $total_amount
//     ]);

