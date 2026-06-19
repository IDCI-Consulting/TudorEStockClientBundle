<?php

namespace IDCI\Bundle\TudorEStockClientBundle\Model;

class Stock
{
    private ?string $id = null;
    private ?string $retailerId = null;
    private ?string $country = null;
    private ?int $value = null;
    private ?string $defaultUrl = null;
    private ?array $localizedUrls = null;
    private ?string $mc = null;
    private ?int $version = null;
    private ?\DateTimeInterface $updatedAt = null;
    private ?bool $storePickupAvailable = null;
    private ?bool $onlinePurchaseEnabled = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRetailerId(): ?string
    {
        return $this->retailerId;
    }

    public function setRetailerId(?string $retailerId): self
    {
        $this->retailerId = $retailerId;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDefaultUrl(): ?string
    {
        return $this->defaultUrl;
    }

    public function setDefaultUrl(?string $defaultUrl): self
    {
        $this->defaultUrl = $defaultUrl;

        return $this;
    }

    public function getLocalizedUrls(): ?array
    {
        return $this->localizedUrls;
    }

    public function setLocalizedUrls(?array $localizedUrls): self
    {
        $this->localizedUrls = $localizedUrls;

        return $this;
    }

    public function getMc(): ?string
    {
        return $this->mc;
    }

    public function setMc(?string $mc): self
    {
        $this->mc = $mc;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isStorePickupAvailable(): ?bool
    {
        return $this->storePickupAvailable;
    }

    public function setStorePickupAvailable(?bool $storePickupAvailable): self
    {
        $this->storePickupAvailable = $storePickupAvailable;

        return $this;
    }

    public function isOnlinePurchaseEnabled(): ?bool
    {
        return $this->onlinePurchaseEnabled;
    }

    public function setOnlinePurchaseEnabled(?bool $onlinePurchaseEnabled): self
    {
        $this->onlinePurchaseEnabled = $onlinePurchaseEnabled;

        return $this;
    }
}
