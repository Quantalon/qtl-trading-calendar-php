<?php

namespace Quantalon\QtlTradingCalendar;

use Exception;
use Carbon\Carbon;

class BaseTradingCalendar
{
    public array $settings;
    public Carbon $startDate;
    public Carbon $endDate;

    public function __construct()
    {
        $this->initSettings();
    }

    public function toDate(Carbon $datetime): Carbon
    {
        $date = $datetime->copy();
        $date->hour = 0;
        $date->minute = 0;
        $date->second = 0;
        return $date;
    }

    public function checkDate(Carbon $date)
    {
        if ($date < $this->startDate or $date > $this->endDate) {
            throw new Exception("Out of Calendar Date Range...");
        }
    }

    public function isWeekend(Carbon $date)
    {
        $weekday = $date->weekday();
        if ($weekday == Carbon::SATURDAY or $weekday == Carbon::SUNDAY) {
            return true;
        }
        return false;
    }

    public function isHoliday(Carbon $date)
    {
        return array_any($this->holidayDates, fn($holidayDate) => $holidayDate->eq($date));
    }

    public function isNoNightTradingDates(Carbon $date)
    {
        return array_any($this->noNightTradingDates, fn($noNightTradingDate) => $noNightTradingDate->eq($date));
    }

    public function hasDayTrading(Carbon $date)
    {
        $this->checkDate($date);
        if ($this->isWeekend($date)) {
            return false;
        }
        if ($this->isHoliday($date)) {
            return false;
        }
        return true;
    }

    public function hasNightTrading(Carbon $date)
    {
        $this->checkDate($date);
        if ($this->isWeekend($date)) {
            return false;
        }
        if ($this->isHoliday($date)) {
            return false;
        }
        if ($this->isNoNightTradingDates($date)) {
            return false;
        }
        return true;
    }

    public function isTradingDay(Carbon $date)
    {
        return $this->hasDayTrading($date) or $this->hasNightTrading($date);
    }

    public function currentTradingDay()
    {
        $now = Carbon::now();
        return $this->getTradingDay($now);
    }

    public function nextTradingDay(Carbon $date, $n=1)
    {
        $count = 0;
        while (true) {
            $date = $date->copy()->addDay();
            if ($this->isTradingDay($date)) {
                $count += 1;
                if ($count >= $n) {
                    return $date;
                }
            }
        }
    }

    public function previousTradingDay(Carbon $date, $n=1)
    {
        $count = 0;
        while (true) {
            $date = $date->copy()->subDay();
            if ($this->isTradingDay($date)) {
                $count += 1;
                if ($count >= $n) {
                    return $date;
                }
            }
        }
    }


}