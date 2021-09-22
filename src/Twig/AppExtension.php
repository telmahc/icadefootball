<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use App\Helper\MatchHelper;
use DateTime;
use DateTimeZone;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('matchDateFR', [$this, 'formatMatchDateFR']),
            new TwigFilter('matchStatusFR', [$this, 'formatMatchStatusFR']),
            new TwigFilter('countLine', [$this, 'countLine']),
        ];
    }

    public function formatMatchDateFR($timestamp, $displayTime = true)
    {
        setlocale(LC_TIME, 'fra', 'fr_FR');
        $datetime = new DateTime(date('Y-m-d H:i:s', $timestamp), new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Europe/Paris'));
        $timestamp = $datetime->getTimestamp();
        if ($timestamp > time() && $displayTime === true) {
            return strftime("%a %d/%m%n%H:%M", $timestamp);
        } else {
            return strftime("%a %d/%m", $timestamp);
        }
    }

    public function formatMatchStatusFR($status)
    {
        return MatchHelper::getMatchStatusFR($status);
    }

    public function countLine($formation)
    {
        return substr_count($formation, '-') + 1;
    }
}
