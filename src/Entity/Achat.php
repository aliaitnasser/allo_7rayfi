<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AchatRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Achat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonce", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;
    
    /**
     * Callback appeler a chaque reservation
     * permet d'initialiser le date de creation et le montant
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        
        if(empty($this->amount)){
            //prix de l'annonce * quantité         
            $this->amount = $this->annonce->getPrice() * $this->quantity;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
