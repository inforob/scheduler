<?php

declare(strict_types=1);


namespace App\Scheduler;

use App\Command\EmailScheduled\SendEmailDaily;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule(name:"email")]
class EmailScheduler implements ScheduleProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly LockFactory $lockFactory
    )
    {}
    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
            ->with(
                RecurringMessage::every('10 seconds', new SendEmailDaily((int)uniqid()))
            )
            ->lock($this->lockFactory->createLock('scheduler-email'))
            ->stateful($this->cache);
    }
}