<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $categoryRelocation = $this->make(111, "Umzüge und Transport");
        $this->make(804040, "Sonstige Umzugsleistungen", $categoryRelocation);

        $this->make(802030, "Abtransport, Entsorgung und Entrümpelung");
        $this->make(411070, "Fensterreinigung");
        $this->make(402020, "Holzdielen schleifen");
        $this->make(108140, "Kellersanierung");

        $manager->flush();
    }

    /**
     * Create a category with given CategoryId and Title
     * @param int $categoryId
     * @param string $title
     * @param Category|null $parent if set, the given category is set as parent of the newly created category
     * @return Category newly created Category
     */
    private function make(int $categoryId, string $title, Category $parent = null): Category
    {
        $category = new Category();
        $category->setCategoryId($categoryId);
        $category->setTitle($title);

        if ($parent != null) {
            $category->setParent($parent);
        }

        $this->manager->persist($category);
        $this->addReference("category-{$categoryId}", $category);

        return $category;
    }
}
