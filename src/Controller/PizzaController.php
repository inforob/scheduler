<?php

namespace App\Controller;

use App\Command\Email\EmailCommand;
use App\Entity\Email;
use App\Repository\EmailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {

    }
    #[Route('/pizza', name: 'app_pizza')]
    public function index(EmailRepository $emailRepository): JsonResponse
    {

        $emailsNotSended = $emailRepository->findBy([
            'sended' => Email::NOT_SENDED
        ], ['createdAt' => 'DESC']);

        foreach ($emailsNotSended as $email) {

            $this->commandBus->dispatch(new EmailCommand($email));
        }

        return $this->json([
            'messages' => 'dispatched'
        ]);

    }
}
