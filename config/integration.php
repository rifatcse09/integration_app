<?php

use App\Enums\AuthType;

return [
    'services' => [
        'mail_chimp' => [
            'authApiEndpoint' => env('MAIL_CHIMP_AUTH_API', 'https://login.mailchimp.com/oauth2/authorize'),
            'authApiTokenEndpoint' => env('MAIL_CHIMP_AUTH_API_TOKEN', 'https://login.mailchimp.com/oauth2/token'),
            'apiMetaDataEndpoint' => env('MAIL_CHIMP_META_DATA_END_POINT', 'https://login.mailchimp.com/oauth2/metadata'),
            'clientId' => env('MAIL_CHIMP_CLIENT_ID', ''),
            'clientSecret' => env('MAIL_CHIMP_CLIENT_SECRET', ''),
            // format client metadata for credential list
            'meta' => [
                AuthType::OAUTH2->value => [
                    'name' => 'meta_data.accountname',
                    'email' => 'meta_data.login.email',
                    'avatar' => 'meta_data.login.avatar',
                ],
                AuthType::CLIENT_SECRET->value => [
                    'name' => 'meta_data.accountname',
                    'email' => 'meta_data.login.email',
                    'avatar' => 'meta_data.login.avatar',
                    'client_id' => 'client_id',
                    'client_secret' => 'client_secret',
                    'redirect_uri' => 'redirect_uri',
                ],
                AuthType::API_KEY->value => [
                    'api_key' => 'api_key',
                ]
            ],
        ],
        'google_sheet' => [
            'files' => env('GOOGLE_FILES', 'https://www.googleapis.com/drive/v3/files'),
            'authApiEndpoint' => env('GOOGLE_AUTH_API', 'https://accounts.google.com/o/oauth2/auth'),
            'authApiTokenEndpoint' => env('GOOGLE_AUTH_API_TOKEN', 'https://oauth2.googleapis.com/token'),
            'apiMetaDataEndpoint' => env('GOOGLE_META_DATA_END_POINT', 'https://www.googleapis.com/oauth2/v3/userinfo'),
            'clientId' => env('GOOGLE_CLIENT_ID', ''),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET', ''),
            'scope' => env('GOOGLE_SCOPE', ''),
            'meta' => [
                AuthType::OAUTH2->value => [
                    'name' => 'meta_data.name',
                    'email' => 'meta_data.email',
                    'avatar' => 'meta_data.picture',
                ],
                AuthType::CLIENT_SECRET->value => [
                    'name' => 'meta_data.name',
                    'email' => 'meta_data.email',
                    'avatar' => 'meta_data.picture',
                    'client_id' => 'client_id',
                    'client_secret' => 'client_secret',
                ]
            ],
        ],
        'shopify' => [
            'special_handlers' => [
                'app/uninstalled' => \App\Jobs\AppUninstalledJob::class,
            ],
            'event_data' => [
                'carts' => [],
                'checkouts' => [],
                'collections' => [],
                'customers' => [],
                'customers_delete' => [],
                'customer_group' => [],
                'draft_orders' => [],
                'fulfillments' => [],
                'orders' => [
                    'id',
                    'email',
                    'closed_at',
                    'created_at',
                    'updated_at',
                    'number',
                    'note',
                    'token',
                    'payment_gateway_names',
                    'total_price',
                    'total_outstanding',
                    'total_tax',
                    'total_weight',
                    'currency',
                    'financial_status',
                    'fulfillment_status',
                    'total_discount',
                    'total_line_items_price',
                    'cart_token',
                    'current_subtotal_price_set.shop_money.amount',
                    'current_total_price_set.shop_money.amount',
                    'billing_address.first_name',
                    'billing_address.address1',
                    'billing_address.phone',
                    'billing_address.city',
                    'billing_address.zip',
                    'billing_address.province',
                    'billing_address.country',
                    'billing_address.last_name',
                    'billing_address.address2',
                    'billing_address.company',
                    'billing_address.latitude',
                    'billing_address.longitude',
                    'billing_address.name',
                    'billing_address.country_code',
                    'billing_address.province_code',
                    'customer.email',
                    'customer.created_at',
                    'customer.updated_at',
                    'customer.first_name',
                    'customer.last_name',
                    'customer.state',
                    'customer.note',
                    'customer.verified_email',
                    'customer.multipass_identifier',
                    'customer.tax_exempt',
                    'customer.phone',
                    'customer.email_marketing_consent.state',
                    'customer.email_marketing_consent.opt_in_level',
                    'customer.email_marketing_consent.consent_updated_at',
                    'customer.sms_marketing_consent',
                    'customer.tags',
                    'customer.currency',
                    'customer.tax_exemptions',
                    'customer.admin_graphql_api_id',
                    'customer.default_address.id',
                    'customer.default_address.customer_id',
                    'customer.default_address.first_name',
                    'customer.default_address.last_name',
                    'customer.default_address.company',
                    'customer.default_address.address1',
                    'customer.default_address.address2',
                    'customer.default_address.city',
                    'customer.default_address.province',
                    'customer.default_address.country',
                    'customer.default_address.zip',
                    'customer.default_address.phone',
                    'customer.default_address.name',
                    'customer.default_address.province_code',
                    'customer.default_address.country_code',
                    'customer.default_address.country_name',
                    'customer.default_address.default',
                    'shipping_address.first_name',
                    'shipping_address.address1',
                    'shipping_address.phone',
                    'shipping_address.city',
                    'shipping_address.zip',
                    'shipping_address.province',
                    'shipping_address.country',
                    'shipping_address.last_name',
                    'shipping_address.address2',
                    'shipping_address.company',
                    'shipping_address.latitude',
                    'shipping_address.longitude',
                    'shipping_address.name',
                    'shipping_address.country_code',
                    'shipping_address.province_code'
                ],
                'products' => [],
                'shops' => [
                    'id',
                    'email',
                    'name',
                    'domain',
                    'plan_display_name',
                ],
                'themes' => [],

            ],
            'topics_data' => [
                'app/uninstalled' => [
                    'selected_fields' => null
                ],
                'carts/create' => [
                    'selected_fields' => null,
                ],
                'carts/update' => [
                    'selected_fields' => null,
                ],
                'checkouts/create' => [
                    'selected_fields' => null,
                ],
                'checkouts/update' => [
                    'selected_fields' => null,
                ],
                'collections/create' => [
                    'selected_fields' => null,
                ],
                'collections/update' => [
                    'selected_fields' => null,
                ],
                'customers/create' => [
                    'selected_fields' => null,
                ],
                'customers/update' => [
                    'selected_fields' => null,
                ],
                'customers/enable' => [
                    'selected_fields' => null,
                ],
                'customers/disable' => [
                    'selected_fields' => null,
                ],
                'customers/delete' => [
                    'selected_fields' => null,
                ],
                'customer_groups/create' => [
                    'selected_fields' => null,
                ],
                'draft_orders/create' => [
                    'selected_fields' => null,
                ],
                'draft_orders/update' => [
                    'selected_fields' => null,
                ],
                'fulfillments/create' => [
                    'selected_fields' => null,
                ],
                'fulfillments/update' => [
                    'selected_fields' => null,
                ],
                'fuulfillment_events/create' => [
                    'selected_fields' => null,
                ],
                'inventory_items/create' => [
                    'selected_fields' => null,
                ],
                'inventory_items/update' => [
                    'selected_fields' => null,
                ],
                'inventory_levels/connect' => [
                    'selected_fields' => null,
                ],
                'inventory_levels/disconnect' => [
                    'selected_fields' => null,
                ],
                'inventory_levels/update' => [
                    'selected_fields' => null,
                ],
                'locations/create' => [
                    'selected_fields' => null,
                ],
                'locations/update' => [
                    'selected_fields' => null,
                ],
                'orders/create' => [
                    'selected_fields' => null,
                ],
                'orders/updated' => [
                    'selected_fields' => null,
                ],
                'orders/cancelled' => [
                    'selected_fields' => null,
                ],
                'orders/fulfilled' => [
                    'selected_fields' => null,
                ],
                'orders/paid' => [
                    'selected_fields' => null,
                ],
                'orders/partially_fulfilled' => [
                    'selected_fields' => null,
                ],
                'order_transactions/create' => [
                    'selected_fields' => null,
                ],
                'products/create' => [
                    'selected_fields' => null,
                ],
                'products/update' => [
                    'selected_fields' => null,
                ],
                'refunds/create' => [
                    'selected_fields' => null,
                ],
                'shop/update' => [
                    'selected_fields' => null,
                ],
                'tender_transactions/create' => [
                    'selected_fields' => null,
                ],
                'themes/create' => [
                    'selected_fields' => null,
                ],
                'themes/update' => [
                    'selected_fields' => null,
                ],
                'orders/edited' => [
                    'selected_fields' => null,
                ],
                'locales/create' => [
                    'selected_fields' => null,
                ],
                'locales/update' => [
                    'selected_fields' => null,
                ],
                'disputes/create' => [
                    'selected_fields' => null,
                ],
                'disputes/update' => [
                    'selected_fields' => null,
                ]

            ]
        ]
    ]
];
