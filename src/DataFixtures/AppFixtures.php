<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Annonce;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Role;
use App\Entity\Comment;
use App\Entity\Achat;
use App\Entity\Rating;

class AppFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        //creation du role admin
        
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        
        //creation de l'utilisateur admin
        
        $adminUser = new User();
        $adminUser->setFirstName('Ali')
                  ->setLastName('Ait Nasser')
                  ->setEmail('aliii.nassser@gmail.com')
                  ->setAddress('Rue 62 N 16 Rabat')
                  ->setAge(30)
                  ->setIntroduction('Une petite presentation')
                  ->setHash($this->encoder->encodePassword($adminUser, "alixisali"))
                  ->setPicture('/images/picture.jpg')
                  ->setPhone('0669945363')
                  ->setCity('rabat')
                  ->setJob('')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);
        
        //gestion des users
        
        $users = [];
        $cities = ['rabat', 'casablanca', 'sale', 'kenitra', 'fes', 'marrakech', 'tanger', 'asilah', 'tetouan', 'agadir', 'laayoun', 'oujda', 'elhoceima'];
        $job = ['plomberie', 'maconnerie', 'climatisation', 'chauffage', 'chauffeur','coiffeur', 'peinture', 'femme de ménage', 'soudeur'];
        $genres = ['male', 'female'];
        
        for ($i = 1; $i <= 40; $i++) {
            $user = new User();
            
            $genre = $faker->randomElement($genres);
            
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99). '.jpg';
            
            $picture .= ($genre == 'male' ? 'men/' : 'wemen/'). $pictureId;
            
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                 ->setLastName($faker->lastName)
                 ->setAddress($faker->address)
                 ->setEmail($faker->email)
                 ->setAge(mt_rand(18, 60))
                 ->setIntroduction($faker->sentence)
                 ->setPhone($faker->phoneNumber)
                 ->setHash($hash)
                 ->setCity($cities[mt_rand(0, count($cities) - 1)])
                 ->setJob($job[mt_rand(0, count($job) -1 )])
                 ->setPicture("/images/plombier.jpg");
            
            $manager->persist($user);
            $users[] = $user;
            
        }
        
        // creation de la notation des utilisateurs
        
        for ($i = 0; $i <= count($users); $i++) {
            
            if (mt_rand(0,2)) {
                
                $author = $users[mt_rand(0, 20)];
                $artisan = $users[mt_rand(21, count($users) - 1)];
                
                $rating = new Rating();
                $rating->setComment($faker->sentence)
                ->setAuthor($author)
                ->setUser($artisan)
                ->setNote(mt_rand(1,5));
                $manager->persist($rating);
            }
            
        }
        
        //gestion des annonces
        
        for ($i = 1; $i <= 30; $i++) {
            $annonce = new Annonce();
            
            $title = $faker->word;
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            
            $user = $users[mt_rand(0, count($users) - 1)];
            
            $annonce->setTitle($title)
                    ->setPrice(mt_rand(50, 500))
                    ->setCoverImage("/images/plomberie.jpg")
                    ->setIntroduction($introduction)
                    ->setAuthor($user);
                    
            
            for ($j = 1; $j <= mt_rand(1, 4); $j++) {
                $image = new Image();
                
                $image->setUrl("/images/plomberie2.jpg")
                      ->setCaption($faker->sentence)
                      ->setAnnonce($annonce);
                
                $manager->persist($image);
            }
            
            //gestion des achats
            
            for ($x = 0; $x <= mt_rand(0, 5); $x++) {
                $achat = new Achat();
                
                $createdAt = $faker->dateTimeBetween('-6 months');
                $quantity = mt_rand(1,5);
                $amount = $annonce->getPrice() * $quantity;
                
                $buyer = $users[mt_rand(0, count($users) - 1)];
                
                $achat->setBuyer($buyer)
                      ->setAnnonce($annonce)
                      ->setAmount($amount)
                      ->setCreatedAt($createdAt)
                      ->setQuantity($quantity);
                
                $manager->persist($achat);
            
            }
            
            //gestion des commentaires
            
            for ($a = 0; $a < mt_rand(1,3); $a++) {
                if(mt_rand(0,1)){
                    $comment = new Comment();
                    
                    $user = $users[mt_rand(0, count($users) - 1)];
                    
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1,5))
                            ->setAuthor($user)
                            ->setAnnonce($annonce);
                    
                    $manager->persist($comment);
               }      
            }
            
            $manager->persist($annonce);     
        }

        $manager->flush();
    }
}
