<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $job = new Job();
        $job->setZipCode($this->getReference("zip-10115"));
        $job->setUser($this->getReference("user-Joe Doe"));
        $job->setCategory($this->getReference("category-802030"));
        $job->setTitle("Ein Auftrag");
        $job->setDescription("Gaaaaanz viel text");

        $job2 = new Job();
        $job2->setZipCode($this->getReference("zip-06895"));
        $job2->setUser($this->getReference("user-Michaela Musterfrau"));
        $job2->setCategory($this->getReference("category-804040"));
        $job2->setTitle("Ein zweiter Auftrag");
        $job2->setDescription("Lorem Ipsum Dolor Sit Amet");

        $manager->persist($job);
        $manager->persist($job2);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            ZipCodeFixture::class,
            UserFixture::class,
        ];
    }
}

