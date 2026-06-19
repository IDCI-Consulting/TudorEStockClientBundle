<?php

namespace IDCI\Bundle\TudorEStockClientBundle\Model;

class Page
{
    private ?int $size = null;
    private ?int $totalElements = null;
    private ?int $totalPages = null;
    private ?int $number = null;

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getTotalElements(): ?int
    {
        return $this->totalElements;
    }

    public function setTotalElements(?int $totalElements): self
    {
        $this->totalElements = $totalElements;

        return $this;
    }

    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    public function setTotalPages(?int $totalPages): self
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }
}
