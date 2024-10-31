<?php

namespace App\Utilities;

use Illuminate\Support\Str;

class UrlManager
{
    protected string $url = '';

    public function __construct(string $url = '')
    {
        if (!blank($url)) {
            $this->processPrefix($url);
        }
    }

    public function setBaseUrl(string $url): self
    {
        $this->url = ltrim(rtrim($url, '/'), '/');

        return $this;
    }

    public function concat(string $uri): self
    {
        $this->url .= $this->processPrefix($uri);

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    protected function processPrefix(string $url, string $prefix = '/'): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return rtrim(ltrim($url, '/'), '/');
        }

        return rtrim(Str::start($url, $prefix), '/');
    }

    protected function processSuffix(string $url, string $suffix = '/'): string
    {
        return ltrim(Str::finish($url, $suffix), '/');
    }
}
