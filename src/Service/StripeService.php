<?php

namespace App\Service;

use Stripe\StripeClient;
use App\Entity\Payment;


class StripeService {

    private StripeClient $stripe;
    private string $secretKey;
    private string $webhookSecret;

    public function __construct( string $secretKey, string $webhookSecret){
        $this->secretKey = $secretKey;
        $this->stripe = new StripeClient($secretKey);
        $this->webhookSecret = $webhookSecret;

    }

    public function createPaymentIntent(Payment $payment): string
    {
        $paymentIntent = $this->stripe->paymentIntent->create([
            'amount'=> (int)($payment->getAmount()*100), // Stripe utilise les centimes
            'currency'=> 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,

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
        match($event->type){
            'payment_intent.succeeded'=> $this->handlePaymentIntentSucceeded($event->data->object),
            'payment_intent.payment_failed'=> $this->handlePaymentIntentFailed($event->data->object),
            default => null,
        };
    } 


}
?>