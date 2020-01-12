<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\EventPayment;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function paymentProcess(Request $request)
    {

        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $token = $request->stripeToken;

        if (empty($token)) {
            // do something
            return;
        }

        $paymentError = [];
        $result = null;
        try {
            $result = Charge::create([
                'amount' => 4500,
                'currency' => 'nzd',
                'description' => 'Test',
                'source' => $token
            ]);
        }
        catch (CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (RateLimitException $e) {
            // Too many requests made to the API too quickly
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (ApiConnectionException $e) {
            // Network communication with Stripe failed
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $paymentError['status'] = $e->getHttpStatus();
            $paymentError['error'] = $e->getError();
            $paymentError['messsage'] = $e->getMessage();
        }
        catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $paymentError['messsage'] = $e->getMessage();
        }

        if (!empty($paymentError)) {
            echo json_encode($paymentError);
            die;
        }

        if (!empty($result)) {
            dd($result);
            $eventpayment = new EventPayment();

            return 1;// paymentid
        }

    }
}
