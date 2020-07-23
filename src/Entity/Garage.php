<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GarageRepository")
 */
class Garage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("group1")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Professional", inversedBy="garages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("group1")
     */
    private $professional;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("group1")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Announce", mappedBy="garage", orphanRemoval=true)
     * @Groups("group1")
     */
    private $announces;

    public function __construct()
    {
        $this->announces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): self
    {
        $this->professional = $professional;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Announce[]
     */
    public function getAnnounces(): Collection
    {
        return $this->announces;
    }

    public function addAnnounce(Announce $announce): self
    {
        if (!$this->announces->contains($announce)) {
            $this->announces[] = $announce;
            $announce->setGarage($this);
        }

        return $this;
    }

    public function removeAnnounce(Announce $announce): self
    {
        if ($this->announces->contains($announce)) {
            $this->announces->removeElement($announce);
            // set the owning side to null (unless already changed)
            if ($announce->getGarage() === $this) {
                $announce->setGarage(null);
            }
        }

        return $this;
    }
}
