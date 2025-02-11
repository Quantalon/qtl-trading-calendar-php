<?php

namespace Quantalon\QtlTradingCalendar;

use Carbon\Carbon;
use Yosymfony\Toml\Toml;

class StockTradingCalendar extends BaseTradingCalendar
{
    public array $holidayDates = [];
    public function initSettings()
    {
        $this->settings = Toml::ParseFile(__DIR__ . '/../resources/stock.toml');
        $this->startDate = Carbon::instance($this->settings['start_date']);
        $this->endDate = Carbon::instance($this->settings['end_date']);
        foreach ($this->settings['holiday_dates'] as $holidayDate) {
            $this->holidayDates[] = Carbon::instance($holidayDate);
        }
    }

    public function hasNightTrading(Carbon $date)
    {
        return false;
    }

    public function getTradingDay(Carbon $datetime)
    {
        $date = $this->toDate($datetime);
        if($this->hasNightTrading($date)) {
            return $date;
        } else {
            return null;
        }
    }
}