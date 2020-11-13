<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listPhone = [
            'Apple' => [
                'iPhone 11 Pro', 'iPhone SE 2',
            ],
            'Samsung' => [
                'Galaxy S20/S20 Plus',
                'Galaxy Note 10',
                'Galaxy Note 10 Plus',
                'S11',
                'S10',
            ],
            'Sony' => [
                'XZ3', 'U12 Plus', 'Mi Mix 3',
            ],
            'Razer' => [
                'Phone 2',
            ],
            'Xiaomi' => [
                'Pixel 3 XL',
                'Find X2',
                'P30 Pro',
            ],
            'Google' => [
                'OnePlus 8 Pro',
            ],
            'Huawei' => [
                'P30', 'Mate 20', 'Mate 20 Pro'
            ],
            'LG' => [
                'Realme X50 5G',  'G7 ThinQ',
            ],
            'Nokia' => [
                'OnePlus 7 Pro',
                'OnePlus 6T',
                'Mi 9',
            ]
        ];

        $listOs = ['Android', 'iOS', 'Tizen', 'Linux',];

        $listSim = ['Nano SIM', 'Micro SIM', 'Standart SIM'];

        $faker = Faker\Factory::create('fr_FR');

        $phoneNames = call_user_func_array('array_merge', $listPhone);

        for ($i = 0; $i < count($phoneNames); $i++) {

            $phone = new Phone();

            $phone->setName($phoneNames[$i]);
            $phone->setBrand($this->getBrandName($phoneNames[$i], $listPhone));
            $phone->setPrice($faker->randomFloat(2, 390, 2300));
            $phone->setStock($faker->randomFloat(0, 0, 3000));
            $phone->setDescription($faker->text($maxNbChars = 200));
            $phone->setOS(($listOs[mt_rand(0, 3)]));
            $phone->setSim($listSim[mt_rand(0, 2)]);
            $phone->setNetwork('LTE');
            $phone->setWifi('Yes');
            $phone->setRearCameraResolution($faker->randomElement([6, 8, 16, 38]) . ' MP');
            $phone->setFrontCameraResolution($faker->randomElement([6, 8, 16, 38]) . ' MP');
            $phone->setDimensions($faker->randomFloat(2, 5, 8)  . '(W) x' . $faker->randomFloat(2, 9, 12) .  '(H) x' . $faker->randomFloat(2, 0.6, 1.4) . '(D) cm');
            $phone->setWeight($faker->numberBetween($min = 100, $max = 250) . ' gr');
            $phone->setDisplayResolution('1080 x 2400');
            $phone->setStorage($faker->randomElement(([32, 64, 128])));

            $manager->persist($phone);
            $manager->flush($phone);
        }
    }

    /**
     * Get the Brand of the phoneName
     * @param string $phoneName 
     * @param array $listPhone 
     * @return string 
     */
    private function getBrandName($phoneName, $listPhone): string
    {
        foreach ($listPhone as $key => $value) {
            if (in_array($phoneName, $value)) return $key;
        }
    }
}
