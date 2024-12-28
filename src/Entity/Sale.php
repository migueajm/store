<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    public ?string $total_amount = null;

    #[ORM\Column]
    public ?\DateTimeImmutable $sale_date = null;

    #[ORM\Column(length: 50)]
    public ?string $payment_method = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "sale")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public ?User $user = null;

    #[ORM\OneToMany(mappedBy: "sale", targetEntity: SaleDetail::class, cascade: ["persist", "remove"])]
    private Collection $details;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    /**
     * @return Collection|SaleDetail[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(SaleDetail $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setSale($this);
        }
        return $this;
    }

    public function removeDetail(SaleDetail $detail): self
    {
        if ($this->details->removeElement($detail)) {
            if ($detail->getSale() === $this) {
                $detail->setSale(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): static
    {
        $this->total_amount = $total_amount;
        return $this;
    }

    public function getSaleDate(): ?\DateTimeImmutable
    {
        return $this->sale_date;
    }

    public function setSaleDate(\DateTimeImmutable $sale_date): static
    {
        $this->sale_date = $sale_date;
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;
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

    public function getData(): array
    {
        $sale = get_object_vars($this);
        $sale['user'] = $this->user->getId();
        $sale['user_name'] = $this->user->getUsername();
        unset($sale['details']);
        return $sale;
    }
}
