<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("group1")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Announce", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $announce;

    /**
     * @ORM\Column(type="text")
     * @Groups("group1")
     */
    private $base64_content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnounce(): ?Announce
    {
        return $this->announce;
    }

    public function setAnnounce(?Announce $announce): self
    {
        $this->announce = $announce;

        return $this;
    }

    public function getBase64Content(): ?string
    {
        return $this->base64_content;
    }


    public function setBase64Content(?string $base64_content): self
    {
        $this->base64_content = $base64_content;

        return $this;
    }
}
