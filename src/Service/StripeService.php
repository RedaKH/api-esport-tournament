<?php

namespace App\Service;

use Stripe\StripeClient;
use App\Entity\Payment;


class StripeService {

    private StripeClient $stripe;

    public function __construct( private string $stripeSecretKey){
        $this->stripe = new StripeClient($stripeSecretKey);

    }

    public function createPaymentIntent(Payment $payment): string
    {
        $paymentIntent = $this->stripe->paymentIntent->create([
            'amount'=> (int)($payment->getAmount()*100), // Stripe utilise les centimes
            'currency'=> 'eur',
            'automatic_payment_methods' => [
                'enabled' = true,

            ],
            'metadata' => [
                'paymentId'=> $payment->getId(),
                'userId'=> $payment->getUser()->getId(),
            ],
        ]);

        return $paymentIntent->client_secret;

    }

    public function handleWebhook (string $payload, string $sigHeader) : void
    {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            $this->stripeWebHookSecret
        );

        //Gérer les différents résultats du paiement
    } 


}
?>