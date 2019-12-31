<?php

declare(strict_types=1);

namespace Aeyoll;

use Cmixin\BusinessDay;
use Carbon\Carbon;

class NextDayDelivery
{
    /** @var int The maximum hour of the day before the business can deliver for tomorrow */
    private $timeLimit;

    public function __construct(int $timeLimit)
    {
        $this->timeLimit = $timeLimit;
    }

    public function getNextBusinessDay(\DateTime $currentDate = null)
    {
        if (is_null($currentDate)) {
            $currentDate = new \DateTime();
        }
        
        $baseList = 'fr';
        $additionalHolidays = [];

        BusinessDay::enable('Carbon\Carbon', $baseList, $additionalHolidays);

        return Carbon::parse($currentDate)->nextBusinessDay();
    }

    public function isNextDayDeliveryPossible(\DateTime $currentDate = null)
    {
        if (is_null($currentDate)) {
            $currentDate = new \DateTime();
        }

        $hour = $currentDate->format('H');

        $tomorrow = new \DateTime('tomorrow');
        $nextBusinessDay = $this->getNextBusinessDay($currentDate);

        if ((int) $nextBusinessDay->diff($tomorrow, true)->format('%d') === 0 && $hour < $this->timeLimit) {
            $maxDelivery = clone $currentDate;
            $maxDelivery->setTime($this->timeLimit, 0);

            $ret = date_diff($currentDate, $maxDelivery, true)->format('%Hh%I');
        } else {
            $ret = false;
        }

        return $ret;
    }
}
