<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LienRepository")
 */
class Lien
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Assert\Url(
     *    message = "l'url '{{ value }}' n'est pas une url valide",
     * )
     */
    private $long;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $court;

    /**
     * @ORM\Column(type="integer")
     */
    private $compteur;

    public function getId()
    {
        return $this->id;
    }

    public function getLong(): ?string
    {
        return $this->long;
    }

    public function setLong(string $long): self
    {
        $this->long = $long;

        return $this;
    }

    public function getCourt(): ?string
    {
        return $this->court;
    }

    public function setCourt(string $court): self
    {
        $this->court = $court;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(int $compteur): self
    {
        $this->compteur = $compteur;

        return $this;
    }
}
