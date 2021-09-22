<?php

namespace App\Tests;

use App\Service\FootballApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

class FootballApiServiceTest extends TestCase
{
    private $parameterBag;

    protected function setUp(): void
    {
        $this->parameterBag = new ParameterBag(['kernel.project_dir' => './', 'api.default_season' => 2021, 'api.default_league' => 61, 'api.url' => '', 'api.key' => '']);
        // $this->containerBag = new ContainerBag(new Container($this->parameterBag));
    }

    public function testApiFixturesResponse204(): void
    {
        $client = new MockHttpClient([
            new MockResponse(json_encode(['get' => 'status', 'response' => []]), [
                'response_headers' => ['content-type' => 'application/json'],
                'http_code' => 204,
            ])
        ]);

        $footballApiService = new FootballApiService($client, $this->parameterBag);

        // Don't forget to delete the local file json if it's exist, otherwise it's gonna file_get_contents
        // If api return http_code 204 // 499 // 500 (other than 200), footBallApiService has to throw an exception with status_code 500
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->expectExceptionMessage('error');
        $footballApiService->getLeagueTeams();
    }
}
