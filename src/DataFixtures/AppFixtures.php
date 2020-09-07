<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("en_EN");

        $categories = ["electro", "shoes", "shirt", "jeans"];

        for ($c = 0; $c < count($categories); $c++) {

            $category = new Category();
            $category->setName($categories[$c]);
            $manager->persist($category);

            for ($u = 0; $u < 10; $u++) {

                $user = new User();
                $user->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setPassword($this->encoder->encodePassword($user, "apiyaapiya"))
                    ->setEmail($faker->email);

                $manager->persist($user);

                for ($p = 1; $p < mt_rand(2, 3); $p++) {

                    $product = new Product();

                    $product->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true))
                        ->setDescription($faker->paragraph($nbSentences = 4, $variableNbSentences = true))
                        ->setImage($faker->imageUrl())
                        ->setPrice($faker->randomNumber(2))
                        ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                        ->setCategory($category)
                        ->setUser($user);

                    $manager->persist($product);
                }

                for ($c = 1; $c < mt_rand(2, 3); $c++) {

                    $comment = new Comment();

                    $comment->setContent($faker->paragraph($nbSentences = 4, $variableNbSentences = true))
                        ->setUser($user)
                        ->setProduct($product);;
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
