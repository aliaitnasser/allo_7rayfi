<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    /**
     * permet de chercher un utilisateur
     * 
     * @Route("/user/search", name="user_search")
     * 
     * @return Response
     */
    public function search(Request $request, UserRepository $userRepo, StatsService $statsService){   
        $user = new User();

        $stats = $statsService->getStats();
        
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){   
 
            $city = $form['city']->getData();
            $job = $form['job']->getData();
            dump($city, $job);
            
            $user = $userRepo->search($city, $job);
        } 
        return $this->render('user/search2.html.twig',[
            'form' => $form->createView(),
            'userSearch' => $user,
            'stats' => $stats
        ]);
    }
    
    /**
     * @Route("/user/{slug}", name="user_show")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(User $user, Request $request, EntityManagerInterface $manager)
    {
        $rating = new Rating();
        $author = $this->getUser();
        
        $form = $this->createForm(RatingType::class, $rating);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $rating->setAuthor($author)
                   ->setUser($user);
            
            $manager->persist($rating);
            $manager->flush();
            
            $this->addFlash(
                'success',
                'Vote commentaire a bien été pris en compte'
                );
        }
        
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
    
    
}
