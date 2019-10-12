<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Transaction;
use Braintree_MerchantAccount;
use App\User;

class PaymentsController extends Controller
{
    //
       public function process(Request $request)
    {
        $payload = $request->input('payload', false);
        $nonce = $payload['nonce'];
        
        $status = Braintree_Transaction::sale([
    	'amount' => '10.00',
    	'paymentMethodNonce' => $nonce,
    	'options' => [
    	    'submitForSettlement' => True
    	]
        ]);

        return response()->json($status);
    }

    public static function addpayment(Request $request) {
            return view('braintreepayments/braintree');
        }
    public static function viewsubscription(Request $request) {
            return view('braintreepayments/subscribe');
        }
        
    public function subscribe(Request $request)
    {

  $merchantAccountParams = [
        'individual' => [
        'firstName' => 'Jane',
        'lastName' => 'Doe',
        'email' => 'jane@14ladders.com',
        'phone' => '5553334444',
        'dateOfBirth' => '1981-11-19',
        'ssn' => '456-45-4567',
        'address' => [
        'streetAddress' => '111 Main St',
        'locality' => 'Chicago',
        'region' => 'IL',
        'postalCode' => '60622'
        ]
        ],
        'business' => [
        'legalName' => 'Jane\'s Ladders',
        'dbaName' => 'Jane\'s Ladders',
        'taxId' => '98-7654321',
        'address' => [
        'streetAddress' => '111 Main St',
        'locality' => 'Chicago',
        'region' => 'IL',
        'postalCode' => '60622'
        ]
        ],
        'funding' => [
        'descriptor' => 'Blue Ladders',
        'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
        'email' => 'funding@blueladders.com',
        'mobilePhone' => '5555555555',
        'accountNumber' => '1123581321',
        'routingNumber' => '071101307'
        ],
        'tosAccepted' => true,
        'masterMerchantAccountId' => "p5sgrknvg7pyk5ms",
        'id' => "blue_ladders_store"
        ];
        $result = Braintree_MerchantAccount::create($merchantAccountParams);
        print_r($result); exit;
    /*
        try {

            $payload = $request->input('payload', false);
            $nonce = $payload['nonce'];

            $user = User::first();
            $user->newSubscription('main', 'bronze')->create($nonce);

            return response()->json(['success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false]);
        }*/
    }

}
