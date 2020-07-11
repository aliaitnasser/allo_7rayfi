<?php
namespace App\Entity;

use App\Entity\Achat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 * 
 * @UniqueEntity(
 *  fields = {"email"},
 *  message = "Un autre utilisateur est deja inscrit avec cette adresse email, merci de la modifier"
 *)
 */

//implements le user interface pour pouvoir encoder le password

class User implements UserInterface
{

    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prenom")
     */
    private $firstName;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $lastName;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner une adresse email valide.")
     */
    private $email;

    /**
     *
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    
    /**
     * @Assert\EqualTo(propertyPath="hash", message="Vous n'avez pas correctement confirmer votre mot de passe")
     */
    public $passwordConfirm;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Votre introduction doit faire au moins 10 caractaires")
     */
    private $introduction;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="Author")
     */
    private $annonces;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Achat", mappedBy="buyer")
     */
    private $achats;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="user", orphanRemoval=true)
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="author", orphanRemoval=true)
     */
    private $no;

    /**
     * permet d'initialiser le slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function initSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }
    
    /**
     * calculer la note moyenne d'un utilisateur
     * 
     * @return number
     */
    
    public function getAvgNotes(){
        // calculer la somme des notes
        
        $sum = array_reduce(
            $this->getRatings()->toArray(), function($total, $note){
           return $total + $note->getNote(); 
        }, 0);
        
        // faire la moyenne
        if(count($this->ratings) > 0) return $sum / count($this->ratings);
        
        return 0;
    }
    
    /**
     * permet de recupere la note d'un author par rapport a un artisan
     * 
     * @param User $author
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Rating[]|NULL
     */
    public function getRatingExist(User $author){
        foreach ($this->ratings as $rating){
            if($rating->getAuthor() === $author) return $rating;
        }
        return null;
    }
    
    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->no = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     *
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (! $this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }

    public function getPassword(){
        return $this->hash;
    }

    public function eraseCredentials()
    {}

    public function getSalt()
    {}

    public function getRoles(){
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();
        
        $roles[] = 'ROLE_USER';
        
        return $roles;
    }

    public function getUsername(){
        return $this->email;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setBuyer($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->contains($achat)) {
            $this->achats->removeElement($achat);
            // set the owning side to null (unless already changed)
            if ($achat->getBuyer() === $this) {
                $achat->setBuyer(null);
            }
        }

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

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(Rating $no): self
    {
        if (!$this->no->contains($no)) {
            $this->no[] = $no;
            $no->setAuthor($this);
        }

        return $this;
    }

    public function removeNo(Rating $no): self
    {
        if ($this->no->contains($no)) {
            $this->no->removeElement($no);
            // set the owning side to null (unless already changed)
            if ($no->getAuthor() === $this) {
                $no->setAuthor(null);
            }
        }

        return $this;
    }
}
