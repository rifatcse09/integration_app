<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CredentialStatus;
use App\Models\App;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class AppService extends BaseService
{
    public function getApps(array $filters = []): Collection
    {
        $query = App::query();

        if (Arr::has($filters, 'name') && !empty($filters['name'])) {
            $name = strtolower($filters['name']);
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $name . '%']);
        }

        if (Arr::has($filters, 'type') && !empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->get();
    }

    public function getAppByUid($uid): ?App
    {
        return App::where('uid', $uid)->first();
    }

    public function getAppByPointer($pointer): ?App
    {
        return App::where('pointer', $pointer)->first();
    }

    public function findAppWithActiveCredByUid(string $uid): Builder|Model
    {
        return App::with([
            'credentials' => function ($query) {
                $query->where(
                    [
                        'status' => CredentialStatus::ACTIVE->value,
                        'shop_id' => shop()->id,
                    ]
                );
            },
            'credentials.actionIntegrations'
        ])->where('uid', $uid)->first();
    }

}
