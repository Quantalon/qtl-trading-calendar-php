<?php

use PHPUnit\Framework\TestCase;
use Quantalon\QtlTradingCalendar\FuturesTradingCalendar;
use Quantalon\QtlTradingCalendar\StockTradingCalendar;
use Carbon\Carbon;

class TradingCalendarTest extends TestCase
{
    public function testFuturesTradingCalendar()
    {
        $calendar = new FuturesTradingCalendar();
        $testDate = Carbon::create(2022, 12, 13);
        $this->assertTrue($calendar->isTradingDay($testDate));
        $this->assertTrue($calendar->hasDayTrading($testDate));
        $this->assertTrue($calendar->hasNightTrading($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 14), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 12), $calendar->previousTradingDay($testDate));

        $testDate = Carbon::create(2022, 12, 10);
        $this->assertFalse($calendar->isTradingDay($testDate));
        $this->assertFalse($calendar->hasDayTrading($testDate));
        $this->assertFalse($calendar->hasNightTrading($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 12), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 9), $calendar->previousTradingDay($testDate));

        $testDate = Carbon::create(2022, 10, 3);
        $this->assertFalse($calendar->isTradingDay($testDate));
        $this->assertFalse($calendar->hasDayTrading($testDate));
        $this->assertFalse($calendar->hasNightTrading($testDate));
        $this->assertEquals(Carbon::create(2022, 10, 10), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 9, 30), $calendar->previousTradingDay($testDate));
    }

    public function testStockTradingCalendar()
    {
        $calendar = new StockTradingCalendar();
        $testDate = Carbon::create(2022, 12, 13);
        $this->assertTrue($calendar->isTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 14), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 12), $calendar->previousTradingDay($testDate));

        $testDate = Carbon::create(2022, 12, 10);
        $this->assertFalse($calendar->isTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 12), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 12, 9), $calendar->previousTradingDay($testDate));

        $testDate = Carbon::create(2022, 10, 3);
        $this->assertFalse($calendar->isTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 10, 10), $calendar->nextTradingDay($testDate));
        $this->assertEquals(Carbon::create(2022, 9, 30), $calendar->previousTradingDay($testDate));
    }
}