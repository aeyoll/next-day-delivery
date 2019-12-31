<?php

declare(strict_types=1);

namespace Aeyoll;

use Cmixin\BusinessDay;
use Carbon\Carbon;

const NEXT_DAY_DELIVERY_DEFAULT_OPTIONS = [
    'timeLimit' => 15,
    'countryCode' => 'fr',
    'additionalHolidays' => [],
    'allowSaturdayDelivery' => false
];

class NextDayDelivery
{
    /** @var int The maximum hour of the day before the business can deliver for tomorrow */
    private $timeLimit;

    /** @var string A 2 letter code picked from https://packagist.org/packages/cmixin/business-day */
    private $countryCode;

    /** @var array Optionnal holidays */
    private $additionalHolidays = [];

    /**
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        $options = array_merge(NEXT_DAY_DELIVERY_DEFAULT_OPTIONS, $args);

        $this->timeLimit = $options['timeLimit'];
        $this->countryCode = $options['countryCode'];
        $this->additionalHolidays = $options['additionalHolidays'];

        Carbon::setWeekendDays([Carbon::SATURDAY, Carbon::SUNDAY]);

        if ($options['allowSaturdayDelivery'] === true) {
            Carbon::setWeekendDays([Carbon::SUNDAY]);
        }

        BusinessDay::enable('Carbon\Carbon', $this->countryCode, $this->additionalHolidays);
    }

    public function getNextBusinessDay(\DateTime $currentDate = null)
    {
        if (is_null($currentDate)) {
            $currentDate = new \DateTime();
        }

        return Carbon::parse($currentDate->format('Y-m-d H:m:s'))->nextBusinessDay();
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

        if ((int) $nextBusinessDay->diff($tomorrow, true)->format('%d') === 0 && $hour < $this->timeLimit && Carbon::parse($currentDate->format('Y-m-d H:m:s'))->isBusinessDay()) {
            $maxDelivery = clone $currentDate;
            $maxDelivery->setTime($this->timeLimit, 0);

            $ret = date_diff($currentDate, $maxDelivery, true)->format('%Hh%I');
        } else {
            $ret = false;
        }

        return $ret;
    }
}
