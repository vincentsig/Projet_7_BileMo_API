<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompanyFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $listCompanies = [
            'Orange', 'SFR', 'Free', 'Amazon', 'Cdiscount', 'MolieFun', 'Allo smartphone', 'Vodafone', 'CheapCell', 'Fnac', 'MeilleurMobile'
        ];


        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < count($listCompanies); $i++) {
            $company = new Company();

            $company->setEmail($faker->email());
            $company->setPassword($this->encoder->encodePassword($company, '12345'));
            $company->setName($listCompanies[$i]);
            $company->setPhone($faker->phoneNumber());

            $this->addReference('company-' . $i, $company);
            $manager->persist($company);

            $manager->flush($company);
        }
    }
}
