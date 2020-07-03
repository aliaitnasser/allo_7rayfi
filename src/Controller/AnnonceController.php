<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Comment;
use App\Form\CommentType;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_index")
     */
    public function index(AnnonceRepository $repo)
    {   
        $annonces = $repo->findAll();
        
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }
    
    /**
     * permet de cree une annonce
     *
     * @Route("annonces/new", name="annonces_create")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request){
        
        $annonce = new Annonce();
        
        $form = $this->createForm(AnnonceType::class, $annonce);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            
            foreach($annonce->getImages() as $image){
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }
            
            $annonce->setAuthor($this->getUser());
            
            $manager->persist($annonce);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistrée !"
                );
            
            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }
        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * permet d'afficher le formulaire d'edition
     * 
     * @Route("/annonces/{slug}/edit", name="annonces_edit")
     * 
     * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor()")
     * 
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request){
        
        $form = $this->createForm(AnnonceType::class, $annonce);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            
            foreach($annonce->getImages() as $image){
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }
            
            $manager->persist($annonce);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été modifiée !"
            );
            
            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }
        
        return $this->render('annonce/edit.html.twig',[
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
    }
    
    /**
     * permet d'afficher une annonce
     * 
     * @Route("/annonces/{slug}", name="annonces_show")
     * 
     * @return Response
     */
    public function show(Annonce $annonce){
        
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);
    }
    
    /**
     * permet de supprimer l'annonce
     * 
     * @Route("/annonces/{slug}/delete", name="annonces_delete")
     * 
     * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor()")
     * 
     * @return Response
     * 
     */
    public function delete(Annonce $annonce){
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($annonce);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimer !"
        );
        
        return $this->redirectToRoute('annonces_index');
    }
    
}
