<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FootballApiService
{
    private $client;
    private $params;
    private $filesystem;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->params = $params;
        $this->filesystem = new Filesystem();
    }

    public function getStatus()
    {
        $endpoint = 'status';
        return $this->getApi($endpoint, []);
    }

    /**
     * Get all fixtures for a season and league
     */
    public function getSeasonMatches($season = null, $league = null)
    {
        // Endpoint for matches
        $endpoint = 'fixtures';
        if ($season === null) {
            // default = 2021
            $season = $this->params->get('api.default_season');
        }
        if ($league === null) {
            // default = 61 => ligue 1
            $league = $this->params->get('api.default_league');
        }

        $parameters = array('season' => $season, 'league' => $league);

        return $this->getApi($endpoint, $parameters);
    }

    /**
     * get all fixtures of a team
     */
    public function getTeamMatches($teamId, $season = null, $league = null)
    {
        $endpoint = 'fixtures';
        if ($season === null) {
            // default = 2021
            $season = $this->params->get('api.default_season');
        }
        if ($league === null) {
            // default = 61 => ligue 1
            $league = $this->params->get('api.default_league');
        }
        $parameters = array('season' => $season, 'league' => $league, 'team' => $teamId);
        return $this->getApi($endpoint, $parameters);
    }

    /**
     * get the details for a match
     */
    public function getMatchDetail($fixtureId)
    {
        $endpoint = 'fixtures';
        $parameter = array('id' => $fixtureId);

        return $this->getApi($endpoint, $parameter);
    }

    /**
     * get the X last games of a team
     */
    public function getTeamLastGames($teamId, $nbLastGames = 2, $season = null, $league = null)
    {
        $endpoint = 'fixtures';
        if ($season === null) {
            // default = 2021
            $season = $this->params->get('api.default_season');
        }
        if ($league === null) {
            // default = 61 => ligue 1
            $league = $this->params->get('api.default_league');
        }
        $parameters = array('season' => $season, 'league' => $league, 'team' => $teamId, 'last' => $nbLastGames);
        return $this->getApi($endpoint, $parameters);
    }

    /**
     * get all the teams of a league and season
     */
    public function getLeagueTeams($season = null, $league = null)
    {
        // Endpoint for matches
        $endpoint = 'teams';
        if ($season === null) {
            // default = 2021
            $season = $this->params->get('api.default_season');
        }
        if ($league === null) {
            // default = 61 => ligue 1
            $league = $this->params->get('api.default_league');
        }

        $parameters = array('season' => $season, 'league' => $league);

        return $this->getApi($endpoint, $parameters);
    }

    /**
     * call the api and "help" for the limits of the api
     */
    private function getApi(string $endpoint, $parameters)
    {
        $rebuildFileName = "";
        foreach ($parameters as $parameter => $value) {
            $rebuildFileName .= $value;
            if ($parameter !== array_key_last($parameters)) {
                $rebuildFileName .= '_';
            }
        }
        $currentTime = time();
        // 86400 = 24h
        $delay = 86400;
        // Path of the json where we save the response, because free api call is limited
        $okPath = $this->params->get('kernel.project_dir') . '/public/api/' . $endpoint . (($rebuildFileName !== "") ? '_'  : "") . $rebuildFileName . '.json';
        $errorPath = $this->params->get('kernel.project_dir') . '/public/api/error_' . $endpoint . (($rebuildFileName !== "") ? '_'  : "") . '.json';
        if ($this->filesystem->exists($okPath)) {
            $path = $okPath;
        } else if ($this->filesystem->exists($errorPath)) {
            $path = $errorPath;
        }
        if (isset($path) && $endpoint != 'status') {
            // If the json exist and endpoint isn't status (free call), we get the content, otherwise, we call the api and save the response in the file
            $file = json_decode(file_get_contents($path), true);
            // If data is older than 24h, then we recall the api otherwise we return the file content
            if ($currentTime - $file['lastCall'] < $delay) {
                return $file['content']['response'];
            }
        }
        try {
            $response = $this->client->request('GET', $this->params->get('api.url') . $endpoint, [
                'headers' => [
                    'x-rapidapi-host' => 'v3.football.api-sports.io',
                    'x-rapidapi-key' => $this->params->get('api.key')
                ],
                'query' => $parameters,
            ]);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'error', null, [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $status = $response->getStatusCode();
        $arrayContent = $response->toArray();
        $array = [
            'status' => $status,
            'lastCall' => $currentTime,
            'content' => $arrayContent,
        ];

        if ($status === Response::HTTP_OK) {
            file_put_contents($okPath, json_encode($array));
        } else if ($status === Response::HTTP_NO_CONTENT || $status === 499 || $status === Response::HTTP_INTERNAL_SERVER_ERROR) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'error', null, [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (!empty($array['content']['response'])) {
            return $array['content']['response'];
        } else {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'error', null, [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
