<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use App\Service\StatsService;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(AnnonceRepository $annonceRepo, UserRepository $userRepo,
        StatsService $statsService)
    {
        $stats = $statsService->getStats();
        
        return $this->render('home/home4.html.twig',[
            'stats' => $stats,
            'annonces' => $annonceRepo->findBestAnnonces(3),
            'users' => $userRepo->findBestUser(3)
        ]);
    }
}
