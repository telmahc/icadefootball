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
    const STATUS = [
        self::MATCH_FINISHED => 'Terminé',
        self::MATCH_CANCELLED => 'Annulé',
        self::MATCH_POSTPONED => 'Reporté',
        self::MATCH_ABANDONED => 'Abandonné',
        self::MATCH_FINISHED_AFTER_EXTRA_TIME => 'Terminé après prolongation',
        self::MATCH_FINISHED_AFTER_PENALTY => 'Terminé après pénalty',
        self::MATCH_TIME_TO_BE_DEFINED => 'A définir',
        self::MATCH_NOT_STARTED => '',
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
