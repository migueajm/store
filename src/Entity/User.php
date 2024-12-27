<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\AuthenticationService;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    private ?string $firstname = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    private ?string $lastname = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    public ?string $password = null;

    private ?string $token = null;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Sale::class, cascade: ["persist", "remove"])]
    private Collection $sale;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->sale =  new ArrayCollection();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }
    public function setUpdatedAt($updated_at): static
    {
        if(is_string($updated_at)) $updated_at = new DateTimeImmutable($updated_at);
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = AuthenticationService::ROLE_USER;
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        if(!$password) return $this;
        $this->password = AuthenticationService::encrypPassword($password);
        return $this;
    }

    public function isAdmin(): bool
    {
        return in_array(AuthenticationService::ROLE_ADMIN, $this->roles);
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getModules(): array
    {
        $modules = [
            ['name' => 'Dashboard', 'path' => 'app_dashboard_index', 'class' => 'DashboardController'],
            ['name' => 'Ventas', 'path' => 'app_sales_index', 'class' => 'SalesController'],
            ['name' => 'Inventario', 'path' => 'app_stocktaking_index', 'class' => 'StocktakingController'],
            ['name' => 'Reportes', 'path' => 'app_report_index', 'class' => 'ReportController'],
            ['name' => 'Cerrar sesión', 'path' => 'app_authentication_sign_out', 'class' => 'AuthenticationController']
        ];
        if (in_array(AuthenticationService::ROLE_ADMIN, $this->roles)) {
            $dasboard = $modules[0];
            unset($modules[0]);
            $admin = [
                $dasboard,
                ['name' => 'Categorias', 'path' => 'app_category_index', 'class' => 'CategoryController'],
                ['name' => 'Productos', 'path' => 'app_product_index', 'class' => 'ProductController'],
                ['name' => 'Usuarios', 'path' => 'app_user_index', 'class' => 'UserController']
            ];
            $modules = array_merge($admin, $modules);
        }
        return $modules;
    }

    /**
     * @return Collection|Venta[]
     */
    public function getVentas(): Collection
    {
        return $this->sale;
    }

    public function addVenta(Sale $sale): self
    {
        if (!$this->sale->contains($sale)) {
            $this->sale[] = $sale;
            $sale->getUser($this);
        }
        return $this;
    }

    public function removeVenta(Sale $sale): self
    {
        if ($this->sale->removeElement($sale)) {
            if ($sale->getUser() === $this) {
                $sale->getUser(null);
            }
        }
        return $this;
    }

    public function getData(): array
    {
        $user = get_object_vars($this);
        unset($user['sale']);
        unset($user['token']);
        unset($user['password']);
        $user['role'] = implode(',', $this->getRoles());
        return $user;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void {}
}
