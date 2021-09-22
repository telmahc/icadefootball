<?php
// src/Controller/SearchController.php
namespace App\Controller;

use App\Helper\SearchHelper;
use App\Service\FootballApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/searchTeams", name="searchTeam")
     */
    public function index(Request $request, FootballApiService $footballApiService): Response
    {
        $teams = $footballApiService->getLeagueTeams();

        $teamsFormatted = SearchHelper::formatTeamsForSearch($teams);
        $term = $request->query->get('term');
        $filteredTeams = SearchHelper::filterTeamsByTerm($term, $teamsFormatted);
        return $this->json($filteredTeams);
    }
}
