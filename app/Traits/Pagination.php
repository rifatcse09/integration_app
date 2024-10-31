<?php

namespace App\Traits;

trait Pagination
{

    protected function paginate(): array
    {
        return [
            'links' => $this->links(),
            'meta' => $this->meta(),
        ];
    }

    protected function links(): array
    {
        $isFirstPage = $this->currentPage() == 1;
        $isLastPage = $this->currentPage() == $this->lastPage();
        $prev = !$isFirstPage ? $this->url(max($this->currentPage() - 1, 1)) : null;
        $next = !$isLastPage ? $this->url(min($this->currentPage() + 1, $this->lastPage())) : null;

        return [
            'first' => $this->url(1),
            'last' => $this->url($this->lastPage()),
            'prev' => $prev,
            'next' => $next,
        ];
    }

    protected function meta(): array
    {
        $from = ($this->currentPage() - 1) * $this->perPage() + 1;
        $to = min($this->currentPage() * $this->perPage(), $this->total());

        return [
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'from' => $from,
            'to' => $to,
            'total' => $this->total(),
        ];
    }
}
