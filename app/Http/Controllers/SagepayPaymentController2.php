<?php

   

namespace App\Http\Controllers;

   
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use Form\Server\Direct;
   

class SagepayPaymentController2 extends Controller

{

    /**

     * success response method.

     */

    public function sagepay2()

    {
		
    	$gateway = OmniPay::create('SagePay\Direct');
		
    	$gateway->setVendor('togiveback');
		$gateway->setTestMode(true);

		$card = new CreditCard([
		    'firstName' => 'Joe',
		    'lastName' => 'Bloggs',
		    'number' => '4484000000002',
		    'expiryMonth' => '12',
		    'expiryYear' => '2020',
		    'cvv' => '123',
		]);
		
		$request = $gateway->createCard([
		    'currency' => 'GBP',
		    'card' => $card,
		    'txtypes'        => array(
				'txtype' => 'DEFERRED',
			),
		]);
		// echo 'here';exit;
		$response = $request->send();
		echo "<pre>"; print_r($response->getToken());
			exit;

		if ($response->isSuccessful()) {
		    $cardReference = $response->getCardReference();
		    // or if you prefer to treat it as a single-use token:
		    $token = $response->getToken();

		    return $token;
		}
		exit;
		$gateway_server = 'SagePay\Server';
    	$gateway = OmniPay::create('SagePay\Server');
		$transactionId = 'phpne-demo-' . rand(10000000, 99999999);
		$_SESSION['transactionId'] = $transactionId;

		$card = new CreditCard([
		    'firstName' => 'Jason',
		    'lastName' => 'Judge',
		    'email' => 'Judge@yopmail.com',
		    // If using SagePay/Server, the credit card details will be
		    // collected direct from the customer, so remove these four entries.
		    'number' => '4929000000006',
		    'expiryMonth' => '10',
		    'expiryYear' => '2023',
		    'CVV' => '123',
		    'billingAddress1' => 'Campus North',
		    'billingAddress2' => '5 Carliol Square',
		    'billingState' => null,
		    'billingCity' => 'Newcastle Upon Tyne',
		    'billingPostcode' => 'NE1',
		    'billingCountry' => 'GB',
		    'shippingAddress1' => 'Campus North',
		    'shippingAddress2' => '5 Carliol Square',
		    'billingState' => null,
		    'shippingCity' => 'Newcastle Upon Tyne',
		    'shippingPostcode' => 'NE1',
		    'shippingCountry' => 'GB',
		]);

		if ($gateway_server == 'SagePay\Direct' || $gateway_server == 'SagePay\Server') {
		    $gateway = OmniPay::create($gateway_server)
		        ->setVendor('togiveback')
		        ->setTestMode(true)
		        ->setReferrerId('3F7A4119-8671-464F-A091-9E59EB47B80C');
		} elseif ($gateway_server == 'AuthorizeNet_SIM' || $gateway_server == 'AuthorizeNet_DPM') {
		    $gateway = OmniPay::create($gateway_server)
		        ->setApiLoginId(getenv('API_LOGIN_ID'))
		        ->setTransactionKey(getenv('TRANSACTION_KEY'))
		        ->setHashSecret(getenv('HASH_SECRET'))
		        ->setTestMode(true)
		        ->setDeveloperMode(true);
		}

		$requestMessage = $gateway->purchase([
		    'amount' => '99.99',
		    'currency' => 'GBP',
		    'card' => $card,
		    'transactionId' => $transactionId,
		    'description' => 'Pizzas for everyone',
		    // No return URL is needed for SagePay\Direct.
		    // It will be needed for SagePay\Server - try it with and without
		    // to see what happens.
		    'returnUrl' => 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
		    // A notify URL is needed for Authorize.Net
		    'notifyUrl' => 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
		    'redirectUrl'=> 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
		    'redirectionUrl'=> 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
		    'notificationUrl'=> 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
		    'serverNotificationUrl'=> 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
		]);

		$responseMessage = $requestMessage->send();

			/*$request = $gateway->createCard([
				    'currency' => 'GBP',
				    'card' => $card,
					'transactionId' => $transactionId,
					'description' => 'Pizzas for everyone',
					// No return URL is needed for SagePay\Direct.
					// It will be needed for SagePay\Server - try it with and without
					// to see what happens.
					'returnUrl' => 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
					// A notify URL is needed for Authorize.Net
					'notifyUrl' => 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
					'redirectUrl'=> 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
					'redirectionUrl'=> 'http://192.168.1.39/goodtogive-web/sagepay_confirm2',
					'notificationUrl'=> 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
					'serverNotificationUrl'=> 'http://192.168.1.39/goodtogive-web/authorizenet_confirm2',
				    'txtype' => 'PAYMENT',
				]);

				$response = $request->send();
				echo "<pre>"; print_r($response->getMessage());
				if ($response->isSuccessful()) {
				    $cardReference = $response->getCardReference();
				    // or if you prefer to treat it as a single-use token:
				    $token = $response->getToken();

				    return $token;
				}
				exit;*/
		/*$transaction = Storage::update($transactionId, [
		    'finalStatus' => 'PENDING',
		    'status' => method_exists($responseMessage, 'getStatus') ? $responseMessage->getStatus() : $responseMessage->getCode(),
		    'message' => 'Awaiting notify',
		    'transactionReference' => $responseMessage->getTransactionReference(),
		]);*/
		
		if ($responseMessage->isSuccessful()) {
			echo "<h2 class='alert alert-success'><span class='glyphicon glyphicon-ok-sign'></span><strong>All finished and all successful.</strong></h2>";
		   // $transaction = Storage::update($transactionId, ['finalStatus' => 'APPROVED']);
		    echo "<p>The final stored transaction:</p>";
		  //  dump($transaction);
		} elseif ($responseMessage->isRedirect()) {
		    // OmniPay provides a POST redirect method for convenience.
		    // You will probably want to write your own that fits in
		    // better with your framework.
		    // Some gateways will be happy with a GET redierect, others will
		    // need a POST redirect, so be aware of that.
		    $responseMessage->redirect();
		} else {
			echo 'sdf3';exit;
		    echo "<h2 class='alert alert-danger'><span class='glyphicon glyphicon-remove-sign'></span>Some kind of error: <strong>" . $responseMessage->getMessage() . "</strong></h2>";
		    $transaction = Storage::update($transactionId, [
		        'finalStatus' => 'ERROR',
		        'status' => $responseMessage->getStatus(),
		        'message' => $responseMessage->getMessage(),
		    ]);
		    echo "<p>The final stored transaction:</p>";
		    dump($transaction);
		}
		echo 'sdf4';exit;
		echo '<p><a href="authorize.php" class="btn btn-default btn-lg">Try again</a></p>';
    }

    public function sagepay_confirm2()

    {
    	print_r($_GET);exit;
    	echo 'sagepay-confirm';
    }

    public function authorizenet_confirm2()

    {
    	echo 'authorizenet-confirm';
    }
}
