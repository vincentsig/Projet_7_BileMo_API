<?php

namespace App\DataFixtures;

use App\Entity\Specification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SpecificationsFixtures extends Fixture
{
    public const SPECIFICATION_REFERENCE = 'spec-ref';



    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        $listOs = ['Android', 'iOS', 'Tizen', 'Linux',];

        $listSim = ['Nano SIM', 'Micro SIM', 'Standart SIM'];

        for ($i = 0; $i < 15; $i++) {
            $spec = new Specification();
            $spec->setOS(($listOs[mt_rand(0, 3)]));
            $spec->setSim($listSim[mt_rand(0, 2)]);
            $spec->setNetwork('LTE');
            $spec->setWifi('Yes');
            $spec->setRearCameraResolution($faker->randomElement([6, 8, 16, 38]) . ' MP');
            $spec->setFrontCameraResolution($faker->randomElement([6, 8, 16, 38]) . ' MP');
            $spec->setDimensions($faker->randomFloat(2, 5, 8)  . '(W) x' . $faker->randomFloat(2, 9, 12) .  '(H) x' . $faker->randomFloat(2, 0.6, 1.4) . '(D) cm');
            $spec->setWeight($faker->numberBetween($min = 100, $max = 250) . ' gr');
            $spec->setDisplayResolution('1080 x 2400');
            $spec->setStorage($faker->randomElement(([32, 64, 128])));
            $manager->persist($spec);
            $this->addReference('spec-' . $i, $spec);
            $manager->flush($spec);
        }
    }
}
