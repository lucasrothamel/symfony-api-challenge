<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\DataFixtures;

use App\Entity\ZipCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ZipCodeFixture extends Fixture
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->make("10115", "Berlin");
        $this->make("32457", "Porta Westfalica");
        $this->make("01623", "Lommatzsch");
        $this->make("21521", "Hamburg");
        $this->make("06895", "Bülzig");
        $this->make("01612", "Diesbar-Seußlitz");

        $manager->flush();
    }

    /**
     * @param string $code German zipcode, eg. 64295
     * @param string $city city for this zipcode, eg. Darmstadt
     */
    private function make(string $code, string $city): void
    {
        $zip = new ZipCode();
        $zip->setCode($code);
        $zip->setCity($city);
        $this->manager->persist($zip);
        $this->addReference("zip-{$code}", $zip);
    }
}

