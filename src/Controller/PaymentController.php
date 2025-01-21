<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Payment;
use App\Service\StripeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;




#[Route('/api')]
class PaymentController extends AbstractController
{
   public function __construct(

    private StripeService $stripeService,
    private EntityManagerInterface $entityManager
   ){}

   #[Route('/payment/create-intent', methods: ['POST'])]
   public function createIntent(Request $request): JsonResponse
   {
   $data = json_decode($request->getContent(), true);

   $payment = new Payment();
   $payment->setAmount($data['amount']);
   $payment->setUser($this->getUser());

   if(isset($data['tournamentId'])){
    $tournament = $this->entityManager->getReference('App:Tournament',$data['tournamentId']);
    $payment->setTournament($tournament);
   }

   if (isset($data['sponsorshipContractId'])) {
    $contract = $this->entityManager->getReference('App:SponsorshipContract',$data['sponsorshipContractId']);
    $payment->setSponsorshipContract($contract);
   
   }

   $this->entityManager->persist($payment);
   $this->entityManager->flush();

   $clientSecret = $this->stripeService->createPaymentIntent($payment);

   return $this->json(['clientSecret'=>$clientSecret]);

}
#[Route('/payment/webhook', methods: ['POST'])]

public function webhook(Request $request): JsonResponse
{
    $payload = $request->getContent();
    $sigHeader = $request->headers->get('Stripe-Signature');

    try {
        $this->stripeService->handleWebhook($payload, $sigHeader);
    } catch (\Exception $e) {
    return $this->json(['error'=>$e->getMessage()],400);
    }
}
}
