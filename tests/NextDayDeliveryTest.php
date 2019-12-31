<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Aeyoll\NextDayDelivery;

final class NextDayDeliveryTest extends TestCase
{
    public function testClassicWeekDayBeforeTimeLimit()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-30 08:00:00')); // Monday before 3pm

        $this->assertEquals('07h00', $isNextDayDeliveryPossible);
    }

    public function testClassicWeekDayAfterTimeLimit()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-30 16:00:00')); // Monday after 3pm
        
        $this->assertEquals(false, $isNextDayDeliveryPossible);
    }

    public function testFridayBeforeTimeLimitIfSaturdayIsOk()
    {
        $helper = new NextDayDelivery(['allowSaturdayDelivery' => true]);
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-13 08:00:00')); // Friday before 3pm
        
        $this->assertEquals('07h00', $isNextDayDeliveryPossible);
    }

    public function testFridayBeforeTimeLimitIfSaturdayIsNotOk()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-13 08:00:00')); // Friday before 3pm
        
        $this->assertEquals(false, $isNextDayDeliveryPossible);
    }

    public function testWeekendDay()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-14 16:00:00')); // Saturday
        $this->assertEquals(false, $isNextDayDeliveryPossible);

        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-16 16:00:00')); // Sunday
        $this->assertEquals(false, $isNextDayDeliveryPossible);
    }

    public function testNextBusinessDayIsAHoliday()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-12-31 08:00:00')); // Tomorrow is a holiday

        $this->assertEquals(false, $isNextDayDeliveryPossible);
    }

    public function testCurrentDayIsAHoliday()
    {
        $helper = new NextDayDelivery();
        $isNextDayDeliveryPossible = $helper->isNextDayDeliveryPossible(new \DateTime('2019-11-01 08:00:00')); // Tomorrow is a holiday
        
        $this->assertEquals(false, $isNextDayDeliveryPossible);
    }
}