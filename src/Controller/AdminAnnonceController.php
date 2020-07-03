<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PaginationService;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonces/{page<\d+>?1}", name="admin_annonces_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Annonce::class)
                    ->setPage($page);
        

        return $this->render('admin/annonce/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Permet de modifier une annonce
     *
     * @Route("/admin/annonces/{id}/edit", name="admin_annonces_edit")
     *
     * @return Response
     */
    public function editAnnonce(Annonce $annonce , Request $request){
        
        $form = $this->createForm(AnnonceType::class, $annonce);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($annonce);
            $manager->flush();
            
          $this->addFlash(
              'success', 
              "l'annonce <strong>{$annonce->getTitle()}</strong> a bien été modifier"
              );
        };
        
        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * permet de supprimer une annonce
     * 
     * @Route("/admin/annonces/{id}/delete", name="admin_annonces_delete")
     * 
     * @return Response
     */
    public function deleteAnnonce(Annonce $annonce){
        
        $manager = $this->getDoctrine()->getManager();
        
        //verifier si l'annonce a été commander par un utilisateur
        
        if(count($annonce->getAchats()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$annonce->getTitle()}</strong> 
                    car elle est déja commander pas des utilisateurs"
                );
        }else{
            
            $manager->remove($annonce);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "l'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimée"
            );
        }
        
        return $this->redirectToRoute('admin_annonces_index');
    }
}





