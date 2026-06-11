<?php

namespace IDCI\Bundle\TudorEStockClientBundle\Model;

class StocksResponse
{
    /**
     * @var array<Stock>
     */
    private array $content = [];
    private ?Page $page = null;

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }
}
