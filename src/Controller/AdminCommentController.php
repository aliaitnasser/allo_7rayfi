<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Comment;
use App\Form\AdminCommentType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PaginationService;

class AdminCommentController extends AbstractController
{
    /**
     * permet d'afficher tout les commentaires 
     * 
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     * 
     * @return Response
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setPage($page)
                    ->setLimit(5);
        
        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    /**
     * permet de modifier un commentaire
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @return Response
     */
    public function edit(Comment $comment, Request $request){
        
        $form = $this->createForm(AdminCommentType::class, $comment);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($comment);
            $manager->flush();
            
            $this->addFlash(
                'success', 
                "Le commentaire numéro <strong>{$comment->getId()}</strong> a bien été modifée"
            );
        };
        
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * 
     */
    public function delete(Comment $comment){
        
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($comment);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimé"
        );
        
        return $this->redirectToRoute('admin_comments_index');
    }
}





