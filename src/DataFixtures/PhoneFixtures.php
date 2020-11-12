<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PhoneFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listPhone = [
            'Apple iPhone 11 Pro', 'iPhone SE 2', 'Samsung Galaxy S20/S20 Plus', 'OnePlus 8 Pro', 'Google Pixel 3 XL', 'Oppo Find X2', 'Huawei P30 Pro',
            'Realme X50 5G', 'Huawei P30', 'Huawei Mate 20', 'Huawei Mate 20 Pro', 'LG G7 ThinQ', 'OnePlus 7 Pro', 'OnePlus 6T', 'Razer Phone 2', 'Samsung Galaxy Note 10 Plus',
            'Samsung Galaxy Note 10', 'Samsung Galaxy S11', 'Samsung Galaxy S10', 'Sony XZ3', 'HTC U12 Plus', 'Xiaomi Mi Mix 3', 'Xiaomi Mi 9'
        ];

        $listBrand = ['Apple', 'Samsung', 'Sony', 'Google', 'Xiaomi', 'Oppo', 'Huawei', 'LG', 'Nokia', 'Razer'];

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < count($listPhone); $i++) {
            $phone = new Phone();
            $phone->setName($listPhone[$i]);
            $phone->setBrand($listBrand[mt_rand('0', '9')]);
            $phone->setPrice($faker->randomFloat(2, 390, 2300));
            $phone->setStock($faker->randomFloat(0, 0, 3000));
            $phone->setDescription($faker->text($maxNbChars = 200));
            $phone->setSpecifications($this->getReference("spec-" . mt_rand('0', '12')));
            $manager->persist($phone);
            $manager->flush($phone);
        }
    }
    public function getDependencies()
    {
        return array(
            SpecificationsFixtures::class,
        );
    }
}
