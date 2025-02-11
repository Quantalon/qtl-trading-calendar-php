<?php

namespace Quantalon\QtlTradingCalendar;

use Yosymfony\Toml\Toml;
use Carbon\Carbon;

class FuturesTradingCalendar extends BaseTradingCalendar
{
    public array $holidayDates = [];
    public array $noNightTradingDates = [];

    public function initSettings()
    {
        $this->settings = Toml::ParseFile(__DIR__ . '/../resources/futures.toml');
        $this->startDate = Carbon::instance($this->settings['start_date']);
        $this->endDate = Carbon::instance($this->settings['end_date']);
        foreach ($this->settings['holiday_dates'] as $holidayDate) {
            $this->holidayDates[] = Carbon::instance($holidayDate);
        }
        foreach ($this->settings['no_night_trading_dates'] as $noNightTradingDate) {
            $this->noNightTradingDates[] = Carbon::instance($noNightTradingDate);
        }
    }

    public function getTradingDay(Carbon $datetime)
    {
        $date = $this->toDate($datetime);
        $hour = $datetime->hour;
        if ($hour >= 3 and $hour < 18) {
            if ($this->hasDayTrading($date)) {
                return $date;
            } else {
                return null;
            }
        } elseif ($hour >= 18) {
            if ($this->hasNightTrading($date)) {
                return $this->nextTradingDay($date);
            } else {
                return null;
            }
        } else {
            $date = $date->copy()->subDay();
            if ($this->hasNightTrading($date)) {
                return $this->nextTradingDay($date);
            } else {
                return null;
            }
        }
    }
}