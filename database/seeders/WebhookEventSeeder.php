<?php

namespace Database\Seeders;

use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebhookEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $appId = DB::table('apps')->where('pointer', 'shopify')->value('id');

        if ($appId) {

            $webhookTopics = [
                'orders/create' => [
                    'name' => 'Order Create',
                    'payload' => [
                        "id" => 820982911946154508,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "tomyrakigi@gmail.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [
                        ],
                        "email" => "tomyrakigil@gmail.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [
                        ],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" => "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => [
                            "visa",
                            "bogus"
                        ],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [
                        ],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "customer" => [
                            "id" => 115310627314723954,
                            "email" => "tomyrakigik@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [
                            ],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851245,
                                "customer_id" => 115310627314723954,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true
                            ]
                        ],
                        "discount_applications" => [
                        ],
                        "fulfillments" => [
                        ],
                        "line_items" => [
                            [
                                "id" => 866550311766439020,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1
                                    ]
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ],
                            [
                                "id" => 141249953214522974,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ],
                        "payment_terms" => null,
                        "refunds" => [
                        ],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884015,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ]
                    ]
                ],
                'orders/cancelled' => [
                    'name' => 'Order Cancelled',
                    'payload' => [
                        "id" => 820982911946154508,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "tomyrakigiyrg@gmail.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [
                        ],
                        "email" => "tomyrakigias@gmail.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [
                        ],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" => "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => [
                            "visa",
                            "bogus"
                        ],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [
                        ],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "customer" => [
                            "id" => 115310627314723954,
                            "email" => "tomyrakigiqw@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [
                            ],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851245,
                                "customer_id" => 115310627314723954,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true
                            ]
                        ],
                        "discount_applications" => [
                        ],
                        "fulfillments" => [
                        ],
                        "line_items" => [
                            [
                                "id" => 866550311766439020,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1
                                    ]
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ],
                            [
                                "id" => 141249953214522974,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ],
                        "payment_terms" => null,
                        "refunds" => [
                        ],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884015,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ]
                    ]
                ],
                'orders/fulfilled' => [
                    'name' => 'Order Fulfilled',
                    'payload' => [
                        "id" => 820982911946154508,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "jond@example.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [
                        ],
                        "email" => "jon@example.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [
                        ],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" => "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => [
                            "visa",
                            "bogus"
                        ],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [
                        ],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "customer" => [
                            "id" => 115310627314723954,
                            "email" => "johndoe@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [
                            ],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851245,
                                "customer_id" => 115310627314723954,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true
                            ]
                        ],
                        "discount_applications" => [
                        ],
                        "fulfillments" => [
                        ],
                        "line_items" => [
                            [
                                "id" => 866550311766439020,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1
                                    ]
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ],
                            [
                                "id" => 141249953214522974,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ],
                        "payment_terms" => null,
                        "refunds" => [
                        ],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884015,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ]
                    ]
                ],
                'orders/updated' => [
                    'name' => 'Order Updated',
                    'payload' => [
                        "id" => 820982911946154508,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "jonx@example.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [
                        ],
                        "email" => "jony@example.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [
                        ],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" => "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => [
                            "visa",
                            "bogus"
                        ],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [
                        ],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "customer" => [
                            "id" => 115310627314723954,
                            "email" => "johndow@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [
                            ],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851245,
                                "customer_id" => 115310627314723954,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true
                            ]
                        ],
                        "discount_applications" => [
                        ],
                        "fulfillments" => [
                        ],
                        "line_items" => [
                            [
                                "id" => 866550311766439020,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1
                                    ]
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ],
                            [
                                "id" => 141249953214522974,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ],
                        "payment_terms" => null,
                        "refunds" => [
                        ],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884015,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ]
                    ]

                ],
                'orders/paid' => [
                    'name' => 'Order Paid',
                    'payload' => [
                        "id" => 820982911946154508,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "jont@example.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [
                        ],
                        "email" => "jonro@example.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [
                        ],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" => "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => [
                            "visa",
                            "bogus"
                        ],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [
                        ],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "20.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "398.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "388.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount" => "0.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "customer" => [
                            "id" => 115310627314723954,
                            "email" => "johnwig@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [
                            ],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851245,
                                "customer_id" => 115310627314723954,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true
                            ]
                        ],
                        "discount_applications" => [
                        ],
                        "fulfillments" => [
                        ],
                        "line_items" => [
                            [
                                "id" => 866550311766439020,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1
                                    ]
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ],
                            [
                                "id" => 141249953214522974,
                                "admin_graphql_api_id" => "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [
                                ],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [
                                ],
                                "duties" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ],
                        "payment_terms" => null,
                        "refunds" => [
                        ],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY"
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884015,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD"
                                    ]
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [
                                ],
                                "discount_allocations" => [
                                ]
                            ]
                        ]
                    ]

                ],
                'orders/partially_fulfilled' => [
                    'name' => 'Order Partially Fulfilled',
                    'payload' => [
                        "id" => 820982911946154500,
                        "admin_graphql_api_id" => "gid://shopify/Order/820982911946154508",
                        "app_id" => null,
                        "browser_ip" => null,
                        "buyer_accepts_marketing" => true,
                        "cancel_reason" => "customer",
                        "cancelled_at" => "2021-12-31T19:00:00-05:00",
                        "cart_token" => null,
                        "checkout_id" => null,
                        "checkout_token" => null,
                        "client_details" => null,
                        "closed_at" => null,
                        "confirmation_number" => null,
                        "confirmed" => false,
                        "contact_email" => "jonsi@example.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "currency" => "USD",
                        "current_subtotal_price" => "398.00",
                        "current_subtotal_price_set" => [
                            "shop_money" => ["amount" => "398.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "398.00", "currency_code" => "USD"],
                        ],
                        "current_total_additional_fees_set" => null,
                        "current_total_discounts" => "0.00",
                        "current_total_discounts_set" => [
                            "shop_money" => ["amount" => "0.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "0.00", "currency_code" => "USD"],
                        ],
                        "current_total_duties_set" => null,
                        "current_total_price" => "398.00",
                        "current_total_price_set" => [
                            "shop_money" => ["amount" => "398.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "398.00", "currency_code" => "USD"],
                        ],
                        "current_total_tax" => "0.00",
                        "current_total_tax_set" => [
                            "shop_money" => ["amount" => "0.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "0.00", "currency_code" => "USD"],
                        ],
                        "customer_locale" => "en",
                        "device_id" => null,
                        "discount_codes" => [],
                        "email" => "jonone@example.com",
                        "estimated_taxes" => false,
                        "financial_status" => "voided",
                        "fulfillment_status" => "pending",
                        "landing_site" => null,
                        "landing_site_ref" => null,
                        "location_id" => null,
                        "merchant_of_record_app_id" => null,
                        "name" => "#9999",
                        "note" => null,
                        "note_attributes" => [],
                        "number" => 234,
                        "order_number" => 1234,
                        "order_status_url" =>
                            "https://jsmith.myshopify.com/548380009/orders/123456abcd/authenticate?key=abcdefg",
                        "original_total_additional_fees_set" => null,
                        "original_total_duties_set" => null,
                        "payment_gateway_names" => ["visa", "bogus"],
                        "phone" => null,
                        "po_number" => null,
                        "presentment_currency" => "USD",
                        "processed_at" => "2021-12-31T19:00:00-05:00",
                        "reference" => null,
                        "referring_site" => null,
                        "source_identifier" => null,
                        "source_name" => "web",
                        "source_url" => null,
                        "subtotal_price" => "388.00",
                        "subtotal_price_set" => [
                            "shop_money" => ["amount" => "388.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "388.00", "currency_code" => "USD"],
                        ],
                        "tags" => "tag1, tag2",
                        "tax_exempt" => false,
                        "tax_lines" => [],
                        "taxes_included" => false,
                        "test" => true,
                        "token" => "123456abcd",
                        "total_discounts" => "20.00",
                        "total_discounts_set" => [
                            "shop_money" => ["amount" => "20.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "20.00", "currency_code" => "USD"],
                        ],
                        "total_line_items_price" => "398.00",
                        "total_line_items_price_set" => [
                            "shop_money" => ["amount" => "398.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "398.00", "currency_code" => "USD"],
                        ],
                        "total_outstanding" => "398.00",
                        "total_price" => "388.00",
                        "total_price_set" => [
                            "shop_money" => ["amount" => "388.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "388.00", "currency_code" => "USD"],
                        ],
                        "total_shipping_price_set" => [
                            "shop_money" => ["amount" => "10.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "10.00", "currency_code" => "USD"],
                        ],
                        "total_tax" => "0.00",
                        "total_tax_set" => [
                            "shop_money" => ["amount" => "0.00", "currency_code" => "USD"],
                            "presentment_money" => ["amount" => "0.00", "currency_code" => "USD"],
                        ],
                        "total_tip_received" => "0.00",
                        "total_weight" => 0,
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "user_id" => null,
                        "billing_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY",
                        ],
                        "customer" => [
                            "id" => 115310627314723950,
                            "email" => "johnqw@gmail.com",
                            "created_at" => null,
                            "updated_at" => null,
                            "first_name" => "John",
                            "last_name" => "Smith",
                            "state" => "disabled",
                            "note" => null,
                            "verified_email" => true,
                            "multipass_identifier" => null,
                            "tax_exempt" => false,
                            "phone" => null,
                            "email_marketing_consent" => [
                                "state" => "not_subscribed",
                                "opt_in_level" => null,
                                "consent_updated_at" => null,
                            ],
                            "sms_marketing_consent" => null,
                            "tags" => "",
                            "currency" => "USD",
                            "tax_exemptions" => [],
                            "admin_graphql_api_id" => "gid://shopify/Customer/115310627314723954",
                            "default_address" => [
                                "id" => 715243470612851200,
                                "customer_id" => 115310627314723950,
                                "first_name" => null,
                                "last_name" => null,
                                "company" => null,
                                "address1" => "123 Elm St.",
                                "address2" => null,
                                "city" => "Ottawa",
                                "province" => "Ontario",
                                "country" => "Canada",
                                "zip" => "K2H7A8",
                                "phone" => "123-123-1234",
                                "name" => "",
                                "province_code" => "ON",
                                "country_code" => "CA",
                                "country_name" => "Canada",
                                "default" => true,
                            ],
                        ],
                        "discount_applications" => [],
                        "fulfillments" => [],
                        "line_items" => [
                            [
                                "id" => 866550311766439000,
                                "admin_graphql_api_id" =>
                                    "gid://shopify/LineItem/866550311766439020",
                                "attributed_staffs" => [
                                    [
                                        "id" => "gid://shopify/StaffMember/902541635",
                                        "quantity" => 1,
                                    ],
                                ],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD",
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => ["amount" => "0.00", "currency_code" => "USD"],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [],
                                "duties" => [],
                                "discount_allocations" => [],
                            ],
                            [
                                "id" => 141249953214522980,
                                "admin_graphql_api_id" =>
                                    "gid://shopify/LineItem/141249953214522974",
                                "attributed_staffs" => [],
                                "current_quantity" => 1,
                                "fulfillable_quantity" => 1,
                                "fulfillment_service" => "manual",
                                "fulfillment_status" => null,
                                "gift_card" => false,
                                "grams" => 567,
                                "name" => "IPod Nano - 8GB",
                                "price" => "199.00",
                                "price_set" => [
                                    "shop_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD",
                                    ],
                                    "presentment_money" => [
                                        "amount" => "199.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "product_exists" => true,
                                "product_id" => 632910392,
                                "properties" => [],
                                "quantity" => 1,
                                "requires_shipping" => true,
                                "sku" => "IPOD2008PINK",
                                "taxable" => true,
                                "title" => "IPod Nano - 8GB",
                                "total_discount" => "0.00",
                                "total_discount_set" => [
                                    "shop_money" => ["amount" => "0.00", "currency_code" => "USD"],
                                    "presentment_money" => [
                                        "amount" => "0.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "variant_id" => 808950810,
                                "variant_inventory_management" => "shopify",
                                "variant_title" => null,
                                "vendor" => null,
                                "tax_lines" => [],
                                "duties" => [],
                                "discount_allocations" => [],
                            ],
                        ],
                        "payment_terms" => null,
                        "refunds" => [],
                        "shipping_address" => [
                            "first_name" => "Steve",
                            "address1" => "123 Shipping Street",
                            "phone" => "555-555-SHIP",
                            "city" => "Shippington",
                            "zip" => "40003",
                            "province" => "Kentucky",
                            "country" => "United States",
                            "last_name" => "Shipper",
                            "address2" => null,
                            "company" => "Shipping Company",
                            "latitude" => null,
                            "longitude" => null,
                            "name" => "Steve Shipper",
                            "country_code" => "US",
                            "province_code" => "KY",
                        ],
                        "shipping_lines" => [
                            [
                                "id" => 271878346596884000,
                                "carrier_identifier" => null,
                                "code" => null,
                                "discounted_price" => "10.00",
                                "discounted_price_set" => [
                                    "shop_money" => ["amount" => "10.00", "currency_code" => "USD"],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "is_removed" => false,
                                "phone" => null,
                                "price" => "10.00",
                                "price_set" => [
                                    "shop_money" => ["amount" => "10.00", "currency_code" => "USD"],
                                    "presentment_money" => [
                                        "amount" => "10.00",
                                        "currency_code" => "USD",
                                    ],
                                ],
                                "requested_fulfillment_service_id" => null,
                                "source" => "shopify",
                                "title" => "Generic Shipping",
                                "tax_lines" => [],
                                "discount_allocations" => [],
                            ],
                        ],
                    ]

                ],
                'customers/create' => [
                    'name' => 'Customers Create',
                    'payload' => [
                        "id" => 706405506930370000,
                        "email" => "biller@gmail.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "first_name" => "Bob",
                        "last_name" => "Biller",
                        "orders_count" => 0,
                        "state" => "disabled",
                        "total_spent" => "0.00",
                        "last_order_id" => null,
                        "note" => "This customer loves ice cream",
                        "verified_email" => true,
                        "multipass_identifier" => null,
                        "tax_exempt" => false,
                        "tags" => "",
                        "last_order_name" => null,
                        "currency" => "USD",
                        "phone" => null,
                        "addresses" => [],
                        "tax_exemptions" => [],
                        "email_marketing_consent" => null,
                        "sms_marketing_consent" => null,
                        "admin_graphql_api_id" => "gid://shopify/Customer/706405506930370084",
                    ]
                ],
                'customers/disable' => [
                    'name' => 'Customers Disable',
                    'payload' => [
                        "id" => 706405506930370000,
                        "email" => "billerb@gmail.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "first_name" => "Bob",
                        "last_name" => "Biller",
                        "orders_count" => 0,
                        "state" => "disabled",
                        "total_spent" => "0.00",
                        "last_order_id" => null,
                        "note" => "This customer loves ice cream",
                        "verified_email" => true,
                        "multipass_identifier" => null,
                        "tax_exempt" => false,
                        "tags" => "",
                        "last_order_name" => null,
                        "currency" => "USD",
                        "phone" => null,
                        "addresses" => [],
                        "tax_exemptions" => [],
                        "email_marketing_consent" => null,
                        "sms_marketing_consent" => null,
                        "admin_graphql_api_id" => "gid://shopify/Customer/706405506930370084",
                    ],

                ],
                'customers/enable' => [
                    'name' => 'Customers Enable',
                    "payload" => [
                        "id" => 706405506930370000,
                        "email" => "bob@biller.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "first_name" => "Bob",
                        "last_name" => "Biller",
                        "orders_count" => 0,
                        "state" => "disabled",
                        "total_spent" => "0.00",
                        "last_order_id" => null,
                        "note" => "This customer loves ice cream",
                        "verified_email" => true,
                        "multipass_identifier" => null,
                        "tax_exempt" => false,
                        "tags" => "",
                        "last_order_name" => null,
                        "currency" => "USD",
                        "phone" => null,
                        "addresses" => [],
                        "tax_exemptions" => [],
                        "email_marketing_consent" => null,
                        "sms_marketing_consent" => null,
                        "admin_graphql_api_id" => "gid://shopify/Customer/706405506930370084",
                    ],

                ],
                'customers/update' => [
                    'name' => 'Customers Update',
                    "payload" => [
                        "id" => 706405506930370000,
                        "email" => "bobbill@biller.com",
                        "created_at" => "2021-12-31T19:00:00-05:00",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "first_name" => "Bob",
                        "last_name" => "Biller",
                        "orders_count" => 0,
                        "state" => "disabled",
                        "total_spent" => "0.00",
                        "last_order_id" => null,
                        "note" => "This customer loves ice cream",
                        "verified_email" => true,
                        "multipass_identifier" => null,
                        "tax_exempt" => false,
                        "tags" => "",
                        "last_order_name" => null,
                        "currency" => "USD",
                        "phone" => null,
                        "addresses" => [],
                        "tax_exemptions" => [],
                        "email_marketing_consent" => null,
                        "sms_marketing_consent" => null,
                        "admin_graphql_api_id" => "gid://shopify/Customer/706405506930370084",
                    ],

                ],
                'products/create' => [
                    'name' => 'Products Create',
                    'payload' => [
                        "admin_graphql_api_id" => "gid://shopify/Product/788032119674292922",
                        "body_html" => "An example T-Shirt",
                        "created_at" => null,
                        "handle" => "example-t-shirt",
                        "id" => 788032119674292900,
                        "product_type" => "Shirts",
                        "published_at" => "2021-12-31T19:00:00-05:00",
                        "template_suffix" => null,
                        "title" => "Example T-Shirt",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "vendor" => "Acme",
                        "status" => "active",
                        "published_scope" => "web",
                        "tags" => "example, mens, t-shirt",
                        "variants" => [
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/642667041472713922",
                                "barcode" => null,
                                "compare_at_price" => "24.99",
                                "created_at" => "2021-12-29T19:00:00-05:00",
                                "fulfillment_service" => "manual",
                                "id" => 642667041472714000,
                                "inventory_management" => "shopify",
                                "inventory_policy" => "deny",
                                "position" => 1,
                                "price" => "19.99",
                                "product_id" => 788032119674292900,
                                "sku" => "example-shirt-s",
                                "taxable" => true,
                                "title" => "Small",
                                "updated_at" => "2021-12-30T19:00:00-05:00",
                                "option1" => "Small",
                                "option2" => null,
                                "option3" => null,
                                "grams" => 200,
                                "image_id" => null,
                                "weight" => 200,
                                "weight_unit" => "g",
                                "inventory_item_id" => null,
                                "inventory_quantity" => 75,
                                "old_inventory_quantity" => 75,
                                "requires_shipping" => true,
                            ],
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/757650484644203962",
                                "barcode" => null,
                                "compare_at_price" => "24.99",
                                "created_at" => "2021-12-29T19:00:00-05:00",
                                "fulfillment_service" => "manual",
                                "id" => 757650484644203900,
                                "inventory_management" => "shopify",
                                "inventory_policy" => "deny",
                                "position" => 2,
                                "price" => "19.99",
                                "product_id" => 788032119674292900,
                                "sku" => "example-shirt-m",
                                "taxable" => true,
                                "title" => "Medium",
                                "updated_at" => "2021-12-31T19:00:00-05:00",
                                "option1" => "Medium",
                                "option2" => null,
                                "option3" => null,
                                "grams" => 200,
                                "image_id" => null,
                                "weight" => 200,
                                "weight_unit" => "g",
                                "inventory_item_id" => null,
                                "inventory_quantity" => 50,
                                "old_inventory_quantity" => 50,
                                "requires_shipping" => true,
                            ],
                        ],
                        "options" => [],
                        "images" => [],
                        "image" => null,
                        "variant_gids" => [
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/757650484644203962",
                                "updated_at" => "2022-01-01T00:00:00.000Z",
                            ],
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/642667041472713922",
                                "updated_at" => "2021-12-31T00:00:00.000Z",
                            ],
                        ]
                    ],
                ],
                'products/update' => [
                    'name' => 'Products Update',
                    'payload' => ["admin_graphql_api_id" => "gid://shopify/Product/788032119674292922",
                        "body_html" => "An example T-Shirt",
                        "created_at" => null,
                        "handle" => "example-t-shirt",
                        "id" => 788032119674292900,
                        "product_type" => "Shirts",
                        "published_at" => "2021-12-31T19:00:00-05:00",
                        "template_suffix" => null,
                        "title" => "Example T-Shirt",
                        "updated_at" => "2021-12-31T19:00:00-05:00",
                        "vendor" => "Acme",
                        "status" => "active",
                        "published_scope" => "web",
                        "tags" => "example, mens, t-shirt",
                        "variants" => [
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/642667041472713922",
                                "barcode" => null,
                                "compare_at_price" => "24.99",
                                "created_at" => "2021-12-29T19:00:00-05:00",
                                "fulfillment_service" => "manual",
                                "id" => 642667041472714000,
                                "inventory_management" => "shopify",
                                "inventory_policy" => "deny",
                                "position" => 1,
                                "price" => "19.99",
                                "product_id" => 788032119674292900,
                                "sku" => "example-shirt-s",
                                "taxable" => true,
                                "title" => "Small",
                                "updated_at" => "2021-12-30T19:00:00-05:00",
                                "option1" => "Small",
                                "option2" => null,
                                "option3" => null,
                                "grams" => 200,
                                "image_id" => null,
                                "weight" => 200,
                                "weight_unit" => "g",
                                "inventory_item_id" => null,
                                "inventory_quantity" => 75,
                                "old_inventory_quantity" => 75,
                                "requires_shipping" => true,
                            ],
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/757650484644203962",
                                "barcode" => null,
                                "compare_at_price" => "24.99",
                                "created_at" => "2021-12-29T19:00:00-05:00",
                                "fulfillment_service" => "manual",
                                "id" => 757650484644203900,
                                "inventory_management" => "shopify",
                                "inventory_policy" => "deny",
                                "position" => 2,
                                "price" => "19.99",
                                "product_id" => 788032119674292900,
                                "sku" => "example-shirt-m",
                                "taxable" => true,
                                "title" => "Medium",
                                "updated_at" => "2021-12-31T19:00:00-05:00",
                                "option1" => "Medium",
                                "option2" => null,
                                "option3" => null,
                                "grams" => 200,
                                "image_id" => null,
                                "weight" => 200,
                                "weight_unit" => "g",
                                "inventory_item_id" => null,
                                "inventory_quantity" => 50,
                                "old_inventory_quantity" => 50,
                                "requires_shipping" => true,
                            ],
                        ],
                        "options" => [],
                        "images" => [],
                        "image" => null,
                        "variant_gids" => [
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/757650484644203962",
                                "updated_at" => "2022-01-01T00:00:00.000Z",
                            ],
                            [
                                "admin_graphql_api_id" =>
                                    "gid://shopify/ProductVariant/642667041472713922",
                                "updated_at" => "2021-12-31T00:00:00.000Z",
                            ],
                        ]
                    ],

                ]

            ];


            foreach ($webhookTopics as $topic => $values) {

                DB::table('webhook_events')->insert([
                    'uid' => str_unique(),
                    'app_id' => $appId,
                    'topic' => $topic,
                    'name' => $values['name'],
                    'payload' => json_encode($values['payload']),
                    'status' => Status::ACTIVE->value,
                ]);
            }
        }
    }
}
