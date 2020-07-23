<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnounceRepository")
 */
class Announce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("group1")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garage", inversedBy="announces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $garage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fuel", inversedBy="announces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("group1")
     */
    private $fuel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("group1")
     */
    private $start_year;

    /**
     * @ORM\Column(type="float")
     * @Groups("group1")
     */
    private $km;

    /**
     * @ORM\Column(type="float")
     * @Groups("group1")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="announces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("group1")
     */
    private $model;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="announce", orphanRemoval=true)
     * @Groups("group1")
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getFuel(): ?Fuel
    {
        return $this->fuel;
    }

    public function setFuel(?Fuel $fuel): self
    {
        $this->fuel = $fuel;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartYear(): ?\DateTimeInterface
    {
        return $this->start_year;
    }

    public function setStartYear(\DateTimeInterface $start_year): self
    {
        $this->start_year = $start_year;

        return $this;
    }

    public function getKm(): ?float
    {
        return $this->km;
    }

    public function setKm(float $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnounce($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAnnounce() === $this) {
                $photo->setAnnounce(null);
            }
        }

        return $this;
    }
}
