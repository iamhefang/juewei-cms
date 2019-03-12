<?php

namespace link\hefang\site\tools\controllers;


use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class TimeController extends BaseController
{
    public function timestamp(string $level = 'second'): BaseView
    {
        return $this->_apiSuccess(
            $level === 'millis' ? TimeHelper::currentTimeMillis() : time()
        );
    }

    public function date(): BaseView
    {
        list($year, $month, $day, $isLeapYear, $week, $timeZone, $timestamp) = explode(
            '-',
            date('Y-m-d-L-w-T-U')
        );
        return $this->_apiSuccess([
            'year' => intval($year),
            'month' => intval($month),
            'day' => intval($day),
            'timeZone' => $timeZone,
            'week' => intval($week),
            'isLeapYear' => $isLeapYear == '1',
            'timestamp' => intval($timestamp)
        ]);
    }
}