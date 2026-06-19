<?php

namespace IDCI\Bundle\TudorEStockClientBundle\Model;

class StocksBatchResponse
{
    public const STATUS_CREATED = 'CREATED';
    public const STATUS_UPDATED = 'UPDATED';
    public const STATUS_FAILED = 'FAILED';

    private ?string $mc = null;
    private ?string $country = null;
    private ?string $status = null;
    private ?string $message = null;
    private ?Stock $stock = null;

    public function getMc(): ?string
    {
        return $this->mc;
    }

    public function setMc(?string $mc): self
    {
        $this->mc = $mc;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
