<?php

namespace Database\Seeders;

use App\Enums\AppType;
use App\Models\App;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        App::insert([
            [
                'uid' => str_unique(),
                'name' => 'Shopify',
                'logo' => 'services_logo/logo_shopify.png',
                'icon' => 'services_logo/icons/icon_shopify.png',
                'disk' => 'public',
                'pointer' => 'shopify',
                'type' => AppType::TRIGGER->value,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'uid' => str_unique(),
                'name' => 'Webhook',
                'logo' => 'services_logo/logo_webhook.png',
                'icon' => 'services_logo/icons/icon_webhook.png',
                'disk' => 'public',
                'pointer' => 'webhook',
                'type' => AppType::TRIGGER->value,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'uid' => str_unique(),
                'name' => 'Mail Chimp',
                'logo' => 'services_logo/logo_mailchimp.png',
                'icon' => 'services_logo/icons/icon_mailchimp.png',
                'disk' => 'public',
                'pointer' => 'mail_chimp',
                'type' => AppType::ACTION->value,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'uid' => str_unique(),
                'name' => 'Active Campaign',
                'logo' => 'services_logo/logo_active_campaign.png',
                'icon' => 'services_logo/icons/icon_active_campaign.png',
                'disk' => 'public',
                'pointer' => 'active_campaign',
                'type' => AppType::ACTION->value,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'uid' => str_unique(),
                'name' => 'Google Sheet',
                'logo' => 'services_logo/logo_google_sheet.png',
                'icon' => 'services_logo/icons/icon_google_sheet.png',
                'disk' => 'public',
                'pointer' => 'google_sheet',
                'type' => AppType::ACTION->value,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

    }
}
