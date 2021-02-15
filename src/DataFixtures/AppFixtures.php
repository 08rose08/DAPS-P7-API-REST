<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        $customers = ['Orange', 'SFR', 'Free'];

        foreach($customers as $customerName){
            $customer = new Customer();
            $customer->setName($customerName)
                    ->setEmail($customerName.'@test.fr')
                    ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$YnVHSnZKRXhWRlJqVDkuMQ$5GRGa+XEmNqd0hQTqZPS5HzSXarVsmwxX5Ufpq9wy+o')
                    ->setRoles(['ROLE_USER']);
            $manager->persist($customer);

            for($i = 1; $i <= 4; $i++){
                $user = new User();
                $user->setName('User n°'.$i.' de '.$customer->getName())
                    ->setEmail($customer->getName().'client'.$i.'@test.fr')
                    ->setCustomer($customer);
                $manager->persist($user);
            }
        }

        for($j = 1; $j <= 10; $j++){
            $product = new Product();
            $product->setName($faker->word)
                    ->setDescription($faker->paragraph($nbSentences = 4, $variableNbSentences = true))
                    ->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 500));
            $manager->persist($product);
        }

        $manager->flush();
    }
}