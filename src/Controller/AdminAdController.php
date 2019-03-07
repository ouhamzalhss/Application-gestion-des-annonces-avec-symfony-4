<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admin_ads_index",requirements={"page":"\d+"})
     */
    public function index(AdRepository $repo, $page = 1,PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                   //->setRoute('admin_ads_index')
                    ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'paginator' => $pagination
        ]);
    }

    /**
     * Permet de modifier l'annonce
     * 
     * @Route("/admin/ads/{id}/edit",name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad,Request $request,ObjectManager $manager){
        $form = $this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->IsSubmitted() && $form->IsValid() ){
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce est modifie avec succes");
        }
        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supp une annonce
     *
     * @Route("/admin/ad/{id}/delete",name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return void
     */
    public function delete(Ad $ad,ObjectManager $manager){
       if(count($ad->getBookings()) > 0 ){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce : <strong>{$ad->getTitle()}</strong> car elle possède dêja des réservations !");
       }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");  
       }
       return $this->redirectToRoute('admin_ads_index');
    }
}
