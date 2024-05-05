<?php

declare(strict_types=1);


namespace App\Command\Email;


use App\Entity\Email as EmailEntity;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
        private readonly EmailRepository $emailRepository
    )
    {}

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailCommand $command):void  {

        $email = (new Email())
            ->from($command->getEmail()->getFromWho())
            ->to($command->getEmail()->getToWho())
            ->subject($command->getEmail()->getSubject())
            ->text($command->getEmail()->getContent());

        $this->mailer->send($email);

        $emailForUpdate = $this->emailRepository->find($command->getEmail()->getId());
        $emailForUpdate->setSended(EmailEntity::SENDED);

        $this->entityManager->persist($emailForUpdate);
        $this->entityManager->flush();
    }
}