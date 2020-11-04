<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 500; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstname());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setCompany($this->getReference('company-' . (mt_rand(0, 10))));
            $manager->persist($user);
        }

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstname());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setCompany($this->getReference('company-test'));
            $manager->persist($user);
        }
        $manager->flush($user);
    }
    public function getDependencies()
    {
        return array(
            CompanyFixtures::class,
        );
    }
}
