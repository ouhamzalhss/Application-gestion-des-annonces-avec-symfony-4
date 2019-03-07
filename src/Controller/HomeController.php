<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    /**
     *@Route("/",name="homepage")
     */
    public function home(AdRepository $repo,UserRepository $userRepo){
       
        return $this->render("home.html.twig",[
            'ads'=> $repo->findBestAds(3),
            'users' => $userRepo->getBestUsers(2)
            ]);
    }
}



?>