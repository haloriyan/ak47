<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\TicketPurchase;
use App\Models\TicketPurchaseItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Xendit\Xendit as Xendit;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function checkout(Request $request) {
        $secretKey = env('XENDIT_MODE') == 'sandbox' ? env('XENDIT_SECRET_KEY_SANDBOX') : env('XENDIT_SECRET_KEY');
        Xendit::setApiKey($secretKey);
        $carts = $request->carts;
        $token = $request->token;
        $method = $request->method;
        $channel = $request->channel;
        $eventID = $request->event_id;
        $user = User::where('token', $token)->first();
        $now = Carbon::now();

        $total = 0;
        $totalPay = 0;

        foreach ($carts as $cart) {
            $total += $cart['total'];
            $totalPay += $cart['total'];
        }

        $paymentReference = "AK_".strtoupper(Str::random(4)).rand(1111, 9999);
        $payemntStatus = "PENDING";

        if ($method == "vfa") {
            $payVa = \Xendit\VirtualAccounts::create([
                'external_id' => $paymentReference,
                'bank_code' => strtoupper($channel),
                'expected_amount' => $totalPay,
                'suggested_amount' => $totalPay,
                'is_single_use' => true,
                'expiration_date' => $now->addDay()->format('Y-m-d H:i:s')
            ]);

            $paymentReference = $payVa['id'];
            $payemntStatus = $payVa['status'];
        } else if ($method == "ewallets") {
            $args = [
                'reference_id' => $paymentReference,
                'external_id' => $paymentReference,
                'currency' => "IDR",
                'amount' => $totalPay,
                'checkout_method' => "ONE_TIME_PAYMENT",
                'ewallet_type' => strtoupper(explode("_", $channel)[1]),
                'channel_code' => strtoupper($channel)
            ];

            if ($channel == "id_ovo") {
                $number = $request->field;
                if ($number[0] == "0") {
                    $number = preg_replace('/^0?/', '+62', $number);
                }
                $args['channel_properties']['mobile_number'] = $number;
            } else if ($channel == "id_jeniuspay") {
                $args['channel_properties']['cashtag'] = $request->field;
            }

            // $response = Http::withBasicAuth($secretKey, '')
            // ->post("https://api.xendit.co/ewallets/charges", $args);
            // $payEWallets = $response->body();
        }

        $saveData = TicketPurchase::create([
            'user_id' => $user->id,
            'event_id' => $eventID,
            'total' => $total,
            'total_pay' => $totalPay,
            'payment_reference' => $paymentReference,
            'payment_method' => $request->method,
            'payment_channel' => $request->channel,
            'payment_field' => $request->field,
            'payment_status' => $payemntStatus,
        ]);

        $items = [];
        foreach ($carts as $cart) {
            for ($i = 0; $i < $cart['quantity']; $i++) {
                $code = Str::random(32);
                $saveItem = TicketPurchaseItem::create([
                    'purchase_id' => $saveData->id,
                    'ticket_id' => $cart['ticket_id'],
                    'event_id' => $eventID,
                    'unique_code' => $code,
                    'has_checked_in' => false,
                ]);
            }
        }

        return response()->json([
            'status' => 200
        ]);
    }
    public function myTransaction(Request $request) {
        $user = User::where('token', $request->token)->first();
        $transactions = TicketPurchase::where('user_id', $user->id)
        ->with(['items', 'event'])->take(25)->get();

        return response()->json([
            'status' => 200,
            'transactions' => $transactions
        ]);
    }
    public function myTicket(Request $request) {
        // 
    }
    public function transactionDetail($id) {
        $transaction = TicketPurchase::where('id', $id)
        ->with(['items.ticket', 'items.holder', 'event'])->first();

        return response()->json([
            'status' => 200,
            'transaction' => $transaction
        ]);
    }
    public function setHolder($transactionID, Request $request) {
        $itemID = $request->item_id;
        $email = $request->holder_email;

        $user = User::where('email', $email)->first();
        if ($user == "") {
            $name = explode("@", $email)[0];
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt("inikatasandi"),
            ]);
        }
        
        $query = TicketPurchaseItem::where('id', $itemID);
        $item = $query->first();

        $query->update([
            'holder_id' => $user->id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => "Berhasil mengatur pemegang tiket kepada " . $user->name,
        ]);
    }
}
