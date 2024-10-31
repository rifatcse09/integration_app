<?php

namespace Database\Seeders;

use App\Enums\ChargeInterval;
use App\Enums\PlanFeature;
use App\Enums\PlanName;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'type' => 'RECURRING',
                'name' => PlanName::FREE->value,
                'price' => 00.00,
                'interval' => ChargeInterval::MONTHLY->value,
                'capped_amount' => 00.01,
                'terms' => "No extra charges are applied. You'll be notified once you reach the monthly impression or visitor limit and you can decide whether to upgrade.",
                'trial_days' => 0,
                'test' => config('app.env') !== 'production' ? 1 : 0,
                'on_install' => true,
                'title' => 'Perfect plan for testing',
                'meta' => [
                    'ability' => [
                        
                    ],
                    'features' => [
                        "en" => [
                            '500 visitors/month',
                            '5000 Impressions/month',
                            'Sales Popup',
                            'Visitor Count',
                            'Sold Count',
                            'Analytics',
                            'Support'
                        ]
                    ],
                ]
            ],
            [
                'type' => 'RECURRING',
                'name' => PlanName::PRO->value,
                'price' => 3.99,
                'interval' => ChargeInterval::MONTHLY->value,
                'capped_amount' => 4.00,
                'terms' => "No extra charges are applied. You'll be notified once you reach the monthly impression or visitor limit and you can decide whether to upgrade.",
                'trial_days' => 7,
                'test' => config('app.env') !== 'production' ? 1 : 0,
                'on_install' => false,
                'title' => 'Perfect plan for pro businesses',
                'meta' => [
                    'ability' => [
                     
                    ],
                    'features' => [
                        "en" => [
                            'Unlimited visitors/month',
                            'Unlimited impressions/month',
                            'Unlimited Campaign',
                            'Translation',
                            'Analytics',
                            'All features',
                            'Support'
                        ]
                    ],
                ]
            ],
            [
                'type' => 'RECURRING',
                'name' => PlanName::PRO->value,
                'price' => 39.99,
                'interval' => ChargeInterval::ANNUAL->value,
                'capped_amount' => 40.00,
                'terms' => "No extra charges are applied. You'll be notified once you reach the yearly impression or visitor limit and you can decide whether to upgrade.",
                'trial_days' => 7,
                'test' => config('app.env') !== 'production' ? 1 : 0,
                'on_install' => false,
                'title' => 'Perfect plan for pro businesses',
                'meta' => [
                    'ability' => [
                       
                    ],
                    'features' => [
                        "en" => [
                            'Unlimited visitors/month',
                            'Unlimited impressions/month',
                            'Unlimited Campaign',
                            'Translation',
                            'Analytics',
                            'All features',
                            'Support'
                        ]
                    ],
                ]
            ],
        ];

        Plan::truncate();
        collect($plans)->each(fn ($plan) => Plan::create($plan));
    }
}
