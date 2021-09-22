<?php

namespace App\Helper;

class SearchHelper
{
    /**
     * Format the teams
     * 
     * @Return array[teamId] = teamName
     */
    public static function formatTeamsForSearch($teams) {
        $formattedTeams = [];
        foreach ($teams as $team) {
            $formattedTeams[$team['team']['id']] = $team['team']['name'];
        }
        return $formattedTeams;
    }

    /**
     * Search the term in the formatted array
     * 
     * @Return array filtered
     */
    public static function filterTeamsByTerm($term, $teams) {
        $term = strtolower($term);
        return array_filter(array_map('strtolower', $teams), function($team) use ($term) {
            return ( strpos($team, $term) !== false );
        });
    }
}
