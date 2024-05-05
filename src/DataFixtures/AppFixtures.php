<?php

namespace App\DataFixtures;

use App\Entity\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $emailsForFixture = json_decode(
            file_get_contents('config/fixtures/emails.json'), true
        );

        foreach ($emailsForFixture as $email) {
            $emailForStore = new Email();
            $emailForStore->setSubject($email['subject']);
            $emailForStore->setFromWho($email['from']);
            $emailForStore->setToWho($email['to']);
            $emailForStore->setContent($email['content']);
            $emailForStore->setSended(Email::NOT_SENDED);

            $manager->persist($emailForStore);
        }
        $manager->flush();
    }
}
