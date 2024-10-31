<?php

namespace App\DTOs;

use InvalidArgumentException;

class CredentialDTO implements BaseDTO
{
    public readonly string $name;
    public readonly int $shopId;
    public readonly array $secrets;
    public readonly string $source;
    public readonly int $status;
    public readonly int $appId;
    public readonly ?array $scopes;
    public readonly ?string $scopeHash;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->shopId = $data['shop_id'];
        $this->secrets = $data['secrets']  ?? [];
        $this->source = $data['source'];
        $this->status = $data['status'] ?? true;
        $this->appId = $data['app_id'];
        $this->scopes = $data['scopes'] ?? null;
        $this->scopeHash = $data['scope_hash'] ?? null;

        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->name) || empty($this->shopId)  || empty($this->appId)) {
            throw new InvalidArgumentException('Invalid data provided for CredentialData DTO');
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'shop_id' => $this->shopId,
            'secrets' => $this->secrets,
            'source' => $this->source,
            'status' => $this->status,
            'app_id' => $this->appId,
            'scopes' => $this->scopes,
            'scope_hash' => $this->scopeHash,
        ];
    }
}
