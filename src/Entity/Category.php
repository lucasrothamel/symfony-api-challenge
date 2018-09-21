<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="category_id_idx", columns={"category_id"})})
 */
class Category implements \JsonSerializable
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(description="unique database id of the category")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="a short, descriptive title for the category")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(description="path to the icon to display this category to the user")
     */
    private $iconPath;

    /**
     * @var string
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="category")
     * @SWG\Property(description="list of jobs in this category")
     */
    private $jobs;

    /**
     * @var Category
     * @ORM\OneToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @SWG\Property(description="if not empty, a link to the parent category of this category")
     */
    private $parent;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @SWG\Property(description="a unique CategoryId provided for each category, usually 8 digits long")
     */
    private $category_id;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIconPath(): ?string
    {
        return $this->iconPath;
    }

    public function setIconPath(?string $iconPath): self
    {
        $this->iconPath = $iconPath;

        return $this;
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setCategory($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getCategory() === $this) {
                $job->setCategory(null);
            }
        }

        return $this;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(Category $parent)
    {
        $this->parent = $parent;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * provide a json serialisable version of the category (associative array)
     * @return array
     */
    public function jsonSerialize(): array
    {
        $parentId = $this->getParent() == null ? null : $this->getParent()->getId();

        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'category_id' => $this->getCategoryId(),
            'icon_path' => $this->getIconPath(),
            'parent_id' => $parentId,
        ];
    }
}
