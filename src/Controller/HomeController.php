<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Service\FootballApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(FootballApiService $footballApiService): Response
    {
        $status = $footballApiService->getStatus();
        dump($status);
        return $this->render('home/index.html.twig', ['status' => $status]);
    }
}
