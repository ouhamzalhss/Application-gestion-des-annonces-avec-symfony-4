<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingsController extends AbstractController
{

     /**
     * @Route("/admin/bookings/{page}", name="admin_bookings_index",requirements={"page":"\d+"})
     */
    public function index(BookingRepository $repo,$page = 1,PaginationService $pagination)
    {
        //$rep = $this->getDoctrine()->getRepository(Comment::class);
        //$bookings = $repo->findAll();

        $pagination->setEntityClass(Booking::class)
                                ->setLimit(6)
                               // ->setRoute('admin_bookings_index')
                                ->setPage($page);

        //dump($paginator->getPage());
        //die();

        return $this->render('admin/bookings/index.html.twig', [
            'paginator' => $pagination
        ]);
    }
    /**
     * Permet d'editer une réservation
     * 
     * @Route("/admin/booking/{id}/edit",name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking,Request $request,ObjectManager $manager){
        $form = $this->createForm(AdminBookingType::class,$booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $booking->setAmount(0);
            $manager->flush();
            $this->addFlash('success',"La reservation n° {$booking->getId()} a bien été mise à jour");
            return $this->redirectToRoute('admin_bookings_index');
        }
        return $this->render('admin/bookings/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    
    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/booking/{id}/delete",name="admin_booking_delete")
     *
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return void
     */
    public function delete(Booking $booking,ObjectManager $manager){
        $manager->remove($booking);
        $manager->flush();
  
        $this->addFlash(
            'success',
            "La réservation a bien été supprimée");
  
          return $this->redirectToRoute("admin_bookings_index");
      }
}
