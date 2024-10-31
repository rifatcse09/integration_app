<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Charge;

class ChargeService extends BaseService
{
    public function __construct()
    {
    }

    /**
     * @param int $id
     * @param array $data
     * @return Charge|null
     */
    public function updateCharge(int $id, array $data): ?Charge
    {
        $charge = Charge::findOrFail($id);

        $charge->update($data);

        return $charge;
    }

    /**
     * @param int $chargeId
     * @return Charge|null
     */
    public function getChargeById(int $chargeId): ?Charge
    {
        $charge = Charge::findOrFail($chargeId);

        return $charge;
    }
}
