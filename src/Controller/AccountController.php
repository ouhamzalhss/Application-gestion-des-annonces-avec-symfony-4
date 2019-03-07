<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gerer le formulaire de connexion
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'email' => $username
        ]);
    }
    
    /**
     * permet de se deconnecter 
     * 
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {
        return $this->render('account/login.html.twig');
    }
    /**
     * Permet d'afficher un formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder){

        $user = new User();


        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($user,$user->getHash() );
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé, vous pouvez maintenent vous connectez!"
            );

            return $this->redirectToRoute("account_login");
        }
        return $this->render('account/registration.html.twig',[
          'form' => $form->createView()
        ]);

    }
    /**
     * Permet d'afficher le formulaire de modification de profile
     *
     * @Route("/account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request,ObjectManager $manager){

        $user = $this->getUser();


        $form = $this->createForm(AccountType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données de profil ont ete modifiee avec succès !"
            );

            return $this->redirectToRoute('account_profile');
        }

        return $this->render("account/profile.html.twig",[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/update-password",name="account_password")
     * 
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function updatePassword(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder){

        $passUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class,$passUpdate);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid() ){
            // on verifie si le mot de passe actuel est correct
            if(!password_verify($passUpdate->getOldPassword(),$user->getHash() )){
                 // generer une erreur

                 $form->get("oldPassword")->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            }else{
                // enregistrer le nouveau mot de passe

                $newPassword = $passUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user,$newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les Mot de passe  a bien ete modifiee avec succès !"
                );

                return $this->redirectToRoute('homepage');
            }

        }

        return $this->render("account/password.html.twig",[
            'form' => $form->createView()
        ]);
    }
    
     /**
      * Permet d'afficher le profile d'utilisateur connecte
      *
      * @Route("/account",name="account_index")
      * @IsGranted("ROLE_USER")
      *
      * @return Response
      */
    public function myaccount(){
        return $this->render("user/index.html.twig",[
            'user' => $this->getUser()
        ]);
    }

    /**
     * permetd'afficher les reservation de utilisateur connecté
     * 
     * @Route("/mesReservation",name="account_reserv")
     * @return void
     */
    public function mesReservation(){
         return $this->render("account/bookings.html.twig");
    }

}
