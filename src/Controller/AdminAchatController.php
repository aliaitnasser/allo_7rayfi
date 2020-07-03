<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AchatRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Achat;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AdminAchatType;
use App\Service\PaginationService;

class AdminAchatController extends AbstractController
{
    /**
     * @Route("/admin/achats/{page<\d+>?1}", name="admin_achats_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Achat::class)
                    ->setPage($page);
        
        return $this->render('admin/achat/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    /**
     * permet de modifier une commande
     * 
     * @Route("/admin/achats/{id}/edit", name="admin_achats_edit")
     * 
     * @return Response
     */
    public function edit(Achat $achat, Request $request){
        
        $form = $this->createForm(AdminAchatType::class, $achat);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $manager = $this->getDoctrine()->getManager();
            
            $achat->setAmount(0);
            
            $manager->persist($achat);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "La commande n° <strong>{$achat->getId()}</strong> a bien été modifiée"
            );
            
            return $this->redirectToRoute('admin_achats_index');         
        }
           
        return $this->render('admin/achat/edit.html.twig', [
            'form' => $form->createView(),
            'achat' => $achat
        ]);
    }
    
    /**
     * permet de supprimer une commande
     * 
     * @Route("/admin/achats/{id}/delete", name="admin_achats_delete")
     * 
     * @return Response
     */
    public function delete(Achat $achat){
        
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($achat);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "La commande a bien été suprimée !"
        );
        
        return $this->redirectToRoute('admin_achats_index');
    }
    
    
}



