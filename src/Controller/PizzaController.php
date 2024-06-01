<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\Email\EmailCommand;
use App\Entity\Email;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus, private readonly EntityManagerInterface $entityManager)
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

    #[Route('/sse', name: 'sse')]
    public function streamTime(): StreamedResponse
    {
        $random_string = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        $data = [
            'message' => $random_string,
            'name' => 'Sadhan Sarker',
            'time' => date('h:i:s'),
            'id' => rand(10, 100),
        ];

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){

            echo 'data: ' . json_encode($data) . "\n\n";
            //echo "retry: 100\n\n"; // no retry would default to 3 seconds.
            //echo "data: Hello There\n\n";
            ob_flush();
            flush();
            //sleep(10);
            usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();

        return $response;
    }

    #[Route('/client', name: 'client_side')]
    public function client(): Response
    {
        return $this->render('event-source/client.html.twig');
    }
}
