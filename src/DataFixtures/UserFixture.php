<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->make("Joe Doe");
        $this->make("Michael Mustermann");
        $this->make("Michaela Musterfrau");

        $manager->flush();
    }

    /**
     * @param string $name name of user to create
     */
    private function make(string $name): void
    {
        $user = new User();
        $user->setName($name);
        $this->manager->persist($user);
        $this->addReference("user-{$name}", $user);
    }
}
