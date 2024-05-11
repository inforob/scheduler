<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\Email\EmailCommand;
use App\Repository\EmailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {

    }
    #[Route('/send', name: 'send_emails', options: ['expose'=>true], methods: ["POST"])]
    public function index(Request $request,EmailRepository $emailRepository): JsonResponse
    {
          $emailsIdForSend = $request->request->all();
          if(!empty($emailsIdForSend)){
                foreach ($emailsIdForSend['email'] as $emailId) {
                    $emailStored = $emailRepository->find(intval($emailId));

                    if(!$emailStored->isSended()) {
                        $this->commandBus->dispatch(new EmailCommand($emailStored));
                    }
                }

                return $this->prepareResponseOk();

          }

          return $this->prepareResponseEmpty();
    }

    #[Route('/list', name: 'list_emails')]
    public function list(EmailRepository $emailRepository): Response
    {
        return $this->render('emails/list.html.twig', [
            'emails' => $emailRepository->findAll()
        ]);
    }

    private function prepareResponseOk(): JsonResponse
    {
        return $this->json([
            'code'=> 200,
            'status'=>'server:ok'
        ]);
    }

    private function prepareResponseEmpty(): JsonResponse
    {
        return $this->json([
            'code'=> 300,
            'status'=>'server:ok'
        ]);
    }
}
