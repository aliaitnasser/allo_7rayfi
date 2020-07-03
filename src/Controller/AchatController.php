<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Form\AchatType;
use App\Form\CommentType;
use App\Entity\Achat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Comment;

class AchatController extends AbstractController
{
    /**
     * @Route("/annonces/{slug}/achat", name="achat_create")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function achat(Annonce $annonce, Request $request)
    {
        $achat = new Achat();
        
        $form = $this->createForm(AchatType::class, $achat);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $user = $this->getUser();
          
            $achat->setBuyer($user)
                  ->setAnnonce($annonce);           
            
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($achat);
            $manager->flush();
            
            return $this->redirectToRoute('achat_show', [
                'id' => $achat->getId(),
                'withAlert' => true
            ]);
        }
        
        return $this->render('achat/achat.html.twig', [
            'annonce' => $annonce,
            'form'   => $form->createView()
        ]);
    }
    
    /**
     * permet d'afficher la page un achat
     * 
     * @Route("/achat/{id}", name="achat_show")
     * 
     * @return Response
     */
    public function show(Achat $achat, Request $request){
        
        $comment = new Comment();
        
        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $manager = $this->getDoctrine()->getManager();
            
            $comment->setAnnonce($achat->getAnnonce())
                    ->setAuthor($this->getUser());
            
            $manager->persist($comment);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Votre commentaire a bien été publier !"
                );
        }
        
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
            'form'    => $form->createView()
        ]);
    }
}





