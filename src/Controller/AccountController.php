<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\UpdatePasswordType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et de gerer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null
        ]);
    }
    
    /**
     * permet de se deconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout(){
        
    }
    
    /**
     * permet d'afficher le form d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder){
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            
            // criptage de mot de passe
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash('success', "Votre compte a bien été créer, vous pouvez vous connecter");
            
            return $this->redirectToRoute('account_login');
        }
        
        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * permet d'affcicher le formulaire d'edition du user
     * 
     * @Route("/account/edit", name="account_edit")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function editProfil(Request $request){
        $user = $this->getUser();
        
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash('success', "Votre compte a bien été modifier");    
        }
        
        return $this->render('account/edituser.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name ="password_update")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder){
        
        $passwordUpdate = new PasswordUpdate();
        
        $user = $this->getUser();
        
        $form = $this->createForm(UpdatePasswordType::class, $passwordUpdate);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            
            //verifier que le oldpassword est le password actuel
            
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                
                //gerer les erreurs 
                
                $form->get('oldPassword')->addError(new FormError("Le mot de passe est incorrect !"));
                 
            }else{
                
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                
                $user->setHash($hash);
                
                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash('success', "Votre mot de passe a bien été modifier");
                
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * permet d'afficher mon profile
     * 
     * @Route("/account", name="my_account")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }
    
    /**
     * afficher les achats d'un utilisateurs
     * 
     * @Route("/account/achats", name = "account_achats")
     * 
     * @return Response
     */
    public function achats(){
        
        return $this->render('account/achats.html.twig');
    }
}



