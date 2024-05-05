<?php

declare(strict_types=1);


namespace App\Command\Email;


use App\Entity\Email;

class EmailCommand
{

    public function __construct(private readonly Email $email){}

    public function getEmail(): Email
    {
        return $this->email;
    }
}