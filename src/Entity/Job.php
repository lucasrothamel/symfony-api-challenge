<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 * @ORM\Table(name="jobs")
 * @ORM\HasLifecycleCallbacks
 */
class Job implements \JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ZipCode
     * @SWG\Property(description="German Zip Code (Postleitzahl) where the job should be executed", required=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\ZipCode", inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Bitte geben Sie eine Postleitzahl an")
     */
    private $zip_code;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Bitte geben Sie einen Auftragstitel ein")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Bitte geben Sie einen aussagekräftigen Auftragstitel von mindestens {{ limit }} Zeichen an",
     *      maxMessage = "Bitte geben Sie einen aussagekräftigen Auftragstitel von maximal {{ limit }} Zeichen an"
     * )
     * @SWG\Property(description="Short title of the job, entered by the user")
     */
    private $title;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Bitte geben Sie eine Beschreibung ein")
     * @Assert\Length(
     *      min = 10,
     *      max = 2000,
     *      minMessage = "Bitte beschreiben Sie Ihren Job etwas ausführlicher. Geben Sie eine Beschreibung mit mindestens {{ limit }} Zeichen an",
     *      maxMessage = "Bitte halten Sie sich etwas kürzer. Die Beschreibung darf nicht länger als {{ limit }} Zeichen sein"
     * )
     * @SWG\Property(description="A description of what the user whishes to have done")
     */
    private $description;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Bitte geben Sie eine Kategorie an")
     * @SWG\Property(description="The category of the job to be done")
     */
    private $category;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Ein Nutzer wird benötigt")
     * @SWG\Property(description="The user who requests this job to be done")
     */
    private $user;

    public function getZipCode(): ?ZipCode
    {
        return $this->zip_code;
    }

    public function setZipCode(?ZipCode $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?\DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdated(new \DateTime('now'));

        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime('now'));
        }
    }

    /**
     * provide a json serialisable version of the job (associative array)
     * includes associated array zip code, category and user for ease of use
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'zip_code' => $this->getZipCode()->jsonSerialize(),
            'category' => $this->getCategory()->jsonSerialize(),
            'user' => $this->getUser()->jsonSerialize(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ];
    }
}
