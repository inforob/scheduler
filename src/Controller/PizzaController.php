<?php

namespace App\Controller;

use App\Command\Email\EmailCommand;
use App\Entity\Email;
use App\Repository\EmailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {

    }
    #[Route('/send', name: 'send_emails', options: ['expose'=>true], methods: ["POST"])]
    public function index(EmailRepository $emailRepository): JsonResponse
    {


//        foreach ($emailsNotSended as $email) {
//
//            $this->commandBus->dispatch(new EmailCommand($email));
//        }

        return $this->json(['code'=> 200,'status'=>'server:ok']);

    }

    #[Route('/list', name: 'list_emails')]
    public function list(EmailRepository $emailRepository): Response
    {
        return $this->render('emails/list.html.twig', [
            'emails' => $emailRepository->findAll()
        ]);
    }
}
