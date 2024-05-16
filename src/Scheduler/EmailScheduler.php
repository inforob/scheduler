<?php

declare(strict_types=1);

// src/Scheduler/SaleTaskProvider.php
namespace App\Scheduler;

use App\Command\EmailScheduled\SendEmailDaily;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule]
class EmailScheduler implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
            ->with(
                RecurringMessage::every('15 seconds', new SendEmailDaily((int)uniqid()))
            );
    }
}