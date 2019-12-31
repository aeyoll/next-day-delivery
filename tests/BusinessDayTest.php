<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Aeyoll\BusinessDayHelper;

final class BusinessDayHelperTest extends TestCase
{
    public function testClassicWeekDayBeforeTimeLimit(): void
    {
        $helper = new BusinessDayHelper(15);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-30 08:00:00'));

        $this->assertEquals($isNextDayDeliveryPossible, '07h00');
    }

    public function testClassicWeekDayAfterTimeLimit(): void
    {
        $helper = new BusinessDayHelper(15);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-30 16:00:00'));
        
        $this->assertEquals($isNextDayDeliveryPossible, false);
    }

    public function testWeekendDay(): void
    {
        $helper = new BusinessDayHelper(15);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-14 16:00:00')); // Saturday
        $this->assertEquals($isNextDayDeliveryPossible, false);

        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-16 16:00:00')); // Sunday
        $this->assertEquals($isNextDayDeliveryPossible, false);
    }

    public function testNextBusinessDayIsAHoliday(): void
    {
        $helper = new BusinessDayHelper(15);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-31 08:00:00')); // Tomorrow is a holiday

        $this->assertEquals($isNextDayDeliveryPossible, false);
    }

    public function testCurrentDayIsAHoliday(): void
    {
        $helper = new BusinessDayHelper(15);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-11-01 08:00:00')); // Tomorrow is a holiday
        
        $this->assertEquals($isNextDayDeliveryPossible, false);
    }
}