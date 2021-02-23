<?php

namespace App\Entity;


use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use App\Repository\CustomerRepository;
use JMS\Serializer\Annotation\Exclude;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 * 
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields={"name"}, message="Cet utilisateur existe déjà")
 * 
 * @OA\Schema(
 *     title="Customer class",
 *     description="Customer class",
 * )
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Exclude
     * 
     * @OA\Property(
     *     description="Id",
     *     title="Id",
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"usersList"})
     * 
     * @OA\Property(
     *     description="Name",
     *     title="Name",
     * )
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     * @Exclude
     * 
     * @OA\Property(
     *     description="Role",
     *     title="Role",
     * )
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     * @Exclude
     * 
     * @OA\Property(
     *     description="Password",
     *     title="Password",
     * )
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Exclude
     * 
     * @OA\Property(
     *     format="email",
     *     description="Email",
     *     title="Email",
     * )
     * @var string
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", orphanRemoval=true)
     * @Exclude
     * 
     * @OA\Property(
     *      type="array",
     *      @OA\Items(ref=@Model(type=User::class))
     * )
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Getter for Id
     * @return integer id current value
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name
     * @return string name current value
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name
     * 
     * @param string $name name value to set
     * @return string name current value
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->name;
    }

    /**
     * Getter for roles
     * @return array roles current value
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Setter for roles
     * 
     * @param array $roles roles value to set
     * @return array roles current value
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Getter for password
     * @return string password current value
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Setter for password
     * 
     * @param string $password password value to set
     * @return string password current value
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * Getter for Email
     * @return string email current value
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for Email
     * 
     * @param string $email email value to set
     * @return string email current value
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for users
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Adder for user
     * @param User $user user value to add
     * @return Collection|User[]
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }
    /**
     * Remover for user
     * @param User $user user value to remove
     * @return Collection|User[]
     */
    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }
}
