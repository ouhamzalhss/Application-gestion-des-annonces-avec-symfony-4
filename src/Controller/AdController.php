<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Créer une nouvelle annonce
     * @Route("/ads/new",name="ads_create")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function createAd(Request $request,ObjectManager $manager){

        $ad = new Ad();

        $form = $this->createForm(AdType::class,$ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($ad->getImages() as $image){

                $image->setAd($ad);

                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            
            $manager->persist($ad);

            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce a bien ete enregistree"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);

        }

        return $this->render('ad/new.html.twig',array(
            'form' => $form->createView(),
        ));

  }
/**
 * Permet d'editer une annonce
 * @Route("/ads/{slug}/edit",name="ads_edit")
 * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()",message="Annonce ne vous appartient pas")
 * @return Response
 */
public function edit(Ad $ad, Request $request,ObjectManager $em){

    $form = $this->createForm(AdType::class,$ad);

    $form->handleRequest($request);

    
    if ($form->isSubmitted() && $form->isValid()) {

        foreach($ad->getImages() as $image){
            $image->setAd($ad);
            $em->persist($image);
        }
        
        $em->persist($ad);
        $em->flush();

        $this->addFlash(
            'success',
            "L'annonce a bien ete modifies"
        );

        return $this->redirectToRoute('ads_show',[
            'slug' => $ad->getSlug()
        ]);

    }

    return $this->render('ad/edit.html.twig',
     ['form'=>$form->createView(),'ad'=>$ad ]
   );
}

    /**
     * Permet d'afficher une annonce
     * @Route("/ads/{slug}",name="ads_show")
     * @return Response
     */
   // public function show($slug, AdRepository $repo){
    public function show(Ad $ad){
        // je recupere l'annonce qui correspond au slug
      // $ad = $repo->findOneBySlug($slug);
       return $this->render('ad/show.html.twig',[
             'ad' => $ad,
       ]);
    }
/**
 * Permet de supprimer une annonce
 * @Route("ads/{slug}/delete",name="ads_delete")
 * @Security("is_granted('ROLE_USER') and user==ad.getAuthor()",message="Vous n'avez pas le droit d'acceder a cette ressource")
 * 
 * @param Ad $ad
 * @param ObjectManager $manager
 * @return Response
 */
    public function delete(Ad $ad, ObjectManager $manager){
        $manager->remove($ad);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien ete modifies"
        );

        return $this->redirectToRoute("ads_index");
    }

}
