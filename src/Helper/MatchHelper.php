<?php

namespace App\Helper;

class MatchHelper
{
    const MATCH_FINISHED = 'FT';
    const MATCH_CANCELLED = 'CANC';
    const MATCH_POSTPONED = 'PST';
    const MATCH_ABANDONED = 'ABD';
    const MATCH_FINISHED_AFTER_EXTRA_TIME = 'AET';
    const MATCH_FINISHED_AFTER_PENALTY = 'PEN';
    const MATCH_TIME_TO_BE_DEFINED = 'TBD';
    const MATCH_NOT_STARTED = 'NS';
    const MATCH_HALFTIME = 'HT';
    const MATCH_FIRSTHALF = '1H';
    const MATCH_SECONDHALF = '2H';
    const MATCH_EXTRATIME = 'ET';
    const MATCH_PENALTY = 'P';
    const MATCH_BREAKTIME = 'BT';
    const MATCH_SUSPENDED = 'SUSP';
    const MATCH_INTERRUPTED = 'INT';
    const MATCH_TECHNICAL_LOSS = 'AWD';
    const MATCH_WALKOVER = 'WO';
    const MATCH_IN_PROGRESS = 'LIVE';
    const STATUS = [
        self::MATCH_FINISHED => 'Terminé',
        self::MATCH_CANCELLED => 'Annulé',
        self::MATCH_POSTPONED => 'Reporté',
        self::MATCH_ABANDONED => 'Abandonné',
        self::MATCH_FINISHED_AFTER_EXTRA_TIME => 'Terminé après prolongation',
        self::MATCH_FINISHED_AFTER_PENALTY => 'Terminé après pénalty',
        self::MATCH_TIME_TO_BE_DEFINED => 'A définir',
        self::MATCH_NOT_STARTED => '',
        self::MATCH_HALFTIME => 'Halftime',
        self::MATCH_FIRSTHALF => 'First Half, Kick Off',
        self::MATCH_SECONDHALF => 'Second Half, 2nd Half Started',
        self::MATCH_EXTRATIME => 'Extra Time',
        self::MATCH_PENALTY => 'Penalty In Progress',
        self::MATCH_BREAKTIME => 'Break Time (in Extra Time)',
        self::MATCH_SUSPENDED => 'Match Suspended',
        self::MATCH_INTERRUPTED => 'Match Interrupted',
        self::MATCH_TECHNICAL_LOSS => 'Technical Loss',
        self::MATCH_WALKOVER => 'WalkOver',
        self::MATCH_IN_PROGRESS => 'In Progress',
    ];


    public static function getMatchStatusFR($status) {
        return self::STATUS[$status];
    }

    public static function sortMatchesByDate($matches) {
        $orderedMatches = array();
        usort($matches, function($match1, $match2) {
            return strtotime($match1['fixture']['date']) - strtotime($match2['fixture']['date']);
        });
        return $matches;
    }
}
