<?php


namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class DashboardService{

    private $manager;
    public function __construct(ObjectManager $manager){
         $this->manager = $manager;
    }

    public function getCountUsers(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    public function getCountAds(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\Ad u')->getSingleScalarResult();
    }
    public function getCountBookings(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\Booking u')->getSingleScalarResult();
    }
    public function getCountComments(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\Comment u')->getSingleScalarResult();
    }

    public function getStats(){
        $users = $this->getCountUsers();
        $ads = $this->getCountAds();
        $bookings = $this->getCountBookings();
        $comments = $this->getCountComments();

        return compact('users','ads','bookings','comments');
    }

    public function getBestAds(){
       return $this->manager->createQuery('
       SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
       FROM App\Entity\Comment c
       JOIN c.ad a
       JOIN a.author u
       GROUP BY a
       ORDER BY note DESC'
       )
       ->setMaxResults(5)
       ->getResult();
    }

    public function getWorstAds(){
       return $this->manager->createQuery('
       SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
       FROM App\Entity\Comment c
       JOIN c.ad a
       JOIN a.author u
       GROUP BY a
       ORDER BY note'
       )
       ->setMaxResults(5)
       ->getResult();
    }
}