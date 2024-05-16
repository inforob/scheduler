<?php

declare(strict_types=1);


namespace App\Command\EmailScheduled;


use App\Command\Email\EmailCommand;
use App\Repository\EmailRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SendEmailDailyHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EmailRepository $emailRepository,
        private readonly MessageBusInterface $commandBus
    ){}

    public function __invoke(SendEmailDaily $command):void  {

        $emailForSend = $this->emailRepository->findByNotYetSended();
        if(!$emailForSend) {
            // Notify finished job (Mercure, WebSockets, something...)
            return;
        }

        $this->commandBus->dispatch(new EmailCommand($emailForSend));
        $this->logger->info(sprintf('email dispatched at %s',(new \DateTime())->format('Y-m-d H:i:s')));

    }
}