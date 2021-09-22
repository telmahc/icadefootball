<?php
// src/Controller/MatchController.php
namespace App\Controller;

use App\Helper\MatchHelper;
use App\Service\FootballApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/matches", name="matches")
     */
    public function matches(FootballApiService $footballApiService): Response
    {
        $matches = $footballApiService->getSeasonMatches();
        $matchesOrdered = MatchHelper::sortMatchesByDate($matches);
        $response = $this->render('match/index.html.twig', ['matches' => $matchesOrdered]);
        $response->setSharedMaxAge(3600);
        return $response;
    }

    /**
     * @Route("/matches/{matchId}", name="match", requirements={"matchId"="\d+"})
     */
    public function match(FootballApiService $footballApiService, $matchId): Response
    {
        $match = $footballApiService->getMatchDetail($matchId)[0];
        $homeId = $match['teams']['home']['id'];
        $awayId = $match['teams']['away']['id'];
        $homeLastMatches = $footballApiService->getTeamLastGames($homeId);
        $awayLastMatches = $footballApiService->getTeamLastGames($awayId);
        $response = $this->render('match/match.html.twig', ['match' => $match, 'homeLastMatches' => $homeLastMatches, 'awayLastMatches' => $awayLastMatches]);
        $response->setSharedMaxAge(3600);
        return $response;
    }

    /**
     * @Route("/matches/team/{teamId}", name="teamMatch", requirements={"teamId"="\d+"})
     */
    public function teamMatches(FootballApiService $footballApiService, $teamId): Response
    {
        $matches = $footballApiService->getTeamMatches($teamId);

        $response = $this->render('match/team.html.twig', ['matches' => $matches]);
        $response->setSharedMaxAge(3600);
        return $response;
    }
}
