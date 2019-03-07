<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
         $faker = Factory::create('FR-fr');
         $users = [];
         $genres = ["male","female"];


         $adminRole = new Role();
         $adminRole->setTitle('ROLE_ADMIN');
         $manager->persist($adminRole);
         

         $adminUser = new User();
         $adminUser->setFirstName('Ouhamza')
                    ->setLastName('Lhouceine')
                    ->setEmail('ouhamza.web.pro@gmail.com')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>')
                    ->setHash($this->encoder->encodePassword($adminUser,'123456'))
                    ->setPicture('https://randomuser.me/api/portraits/men/38.jpg')
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);
     


            for($i=0;$i<=10; $i++){
            $user = new User();
            $picture = 'https://randomuser.me/api/portraits/';
            $genre = $faker->randomElement($genres);
            $picturedId = $faker->numberBetween(0,99).'.jpg';
            $picture .= ($genre=="male" ? 'men/' : 'women/').$picturedId;
            $hash = $this->encoder->encodePassword($user,'123456');

            $user->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>')
                    ->setHash($hash)
                    ->setPicture($picture);

                $manager->persist($user);
                $users[] = $user;
         } 

            for ($i = 0; $i < 20; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(640,480);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';
            $price = mt_rand(5, 15);
            $rooms = mt_rand(1, 5);
            $author = $users[mt_rand(0, count($users)-1 )];


            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice($price)
                ->setRooms($rooms)
                ->setAuthor($author);


                // Gestion des images 
                for($j = 0; $j <= mt_rand(1, 5); $j++){
                    $image = new Image();
                    $image->setUrl($faker->imageUrl(640,480))
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                    $manager->persist($image);
                }

                // Gestion des reservations 
                for($j = 0; $j <= mt_rand(1, 10); $j++){
                    $booking = new Booking();

                    $createdAt = $faker->dateTimeBetween('-6 months');
                    $startDate = $faker->dateTimeBetween('-3 months');
                    // Gestion la date fin
                    $duration  =  mt_rand(1, 10);
                    $endDate   = (clone $startDate)->modify("+$duration days");

                    $amount    = $ad->getPrice() * $duration;
                    $booker    = $users[mt_rand(0, count($users)-1 )];
                    $comment   = $faker->paragraph();

                    $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setAmount($amount)
                        ->setCreatedAt($createdAt)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setComment($comment);

                    $manager->persist($booking);
                

                    // Gérer les commentaires
                    $comment = new Comment();
                    $rating = mt_rand(1, 5);
                    $content   = $faker->paragraph();
                    $author = $users[mt_rand(0, count($users)-1 )];
                    $comment->setRating($rating)
                            ->setContent($content)
                            ->setAuthor($author)
                            ->setAd($ad)
                            ->setContent($content);

                    $manager->persist($comment);
                } 

            $manager->persist($ad);

        }

            $manager->flush();
    }
}
