<?php

declare(strict_types=1);

namespace Aeyoll;

use Cmixin\BusinessDay;
use Carbon\Carbon;

class NextDayDelivery
{
    /** @var int The maximum hour of the day before the business can deliver for tomorrow */
    private $timeLimit;

    /** @var string A 2 letter code picked from https://packagist.org/packages/cmixin/business-day */
    private $countryCode;

    /** @var array Optionnal holidays */
    private $additionalHolidays = [];

    public function __construct(int $timeLimit, string $countryCode, array $additionalHolidays = [])
    {
        $this->timeLimit = $timeLimit;
        $this->countryCode = $countryCode;
        $this->additionalHolidays = $additionalHolidays;

        // Allow delivery on saturdays
        Carbon::setWeekendDays([Carbon::SUNDAY]);

        BusinessDay::enable('Carbon\Carbon', $this->countryCode, $this->additionalHolidays);
    }

    public function getNextBusinessDay(\DateTime $currentDate = null)
    {
        if (is_null($currentDate)) {
            $currentDate = new \DateTime();
        }

        return Carbon::parse($currentDate)->nextBusinessDay();
    }

    public function isNextDayDeliveryPossible(\DateTime $currentDate = null)
    {
        if (is_null($currentDate)) {
            $currentDate = new \DateTime();
        }

        $hour = $currentDate->format('H');

        $tomorrow = clone $currentDate;
        $tomorrow->modify('+1 day');

        $nextBusinessDay = $this->getNextBusinessDay($currentDate);

        if ((int) $nextBusinessDay->diff($tomorrow, true)->format('%d') === 0 && $hour < $this->timeLimit && Carbon::parse($currentDate)->isBusinessDay()) {
            $maxDelivery = clone $currentDate;
            $maxDelivery->setTime($this->timeLimit, 0);

            $ret = date_diff($currentDate, $maxDelivery, true)->format('%Hh%I');
        } else {
            $ret = false;
        }

        return $ret;
    }
}
