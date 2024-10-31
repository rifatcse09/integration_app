<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | (Not yet complete) A verbose logged output of processes.
    |
    */

    'debug' => (bool) env('SHOPIFY_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Manual migrations
    |--------------------------------------------------------------------------
    |
    | This option allows you to use:
    | `php artisan vendor:publish --tag=shopify-migrations` to push migrations
    | to your app's folder so you're free to modify before migrating.
    |
    */

    'manual_migrations' => (bool) env('SHOPIFY_MANUAL_MIGRATIONS', false),

    /*
    |--------------------------------------------------------------------------
    | Manual routes
    |--------------------------------------------------------------------------
    |
    | This option allows you to ignore the package's built-in routes.
    | Use `false` (default) for allowing the built-in routes. Otherwise, you
    | can list out which route "names" you would like excluded.
    | See `resources/routes/shopify.php` and `resources/routes/api.php`
    | for a list of available route names.
    | Example: `home,billing` would ignore both "home" and "billing" routes.
    |
    | Please note that if you override the route names
    | (see "route_names" below), the route names that are used in this
    | option DO NOT change!
    |
    */

    'manual_routes' => env('SHOPIFY_MANUAL_ROUTES', false),

    /*
    |--------------------------------------------------------------------------
    | Route names
    |--------------------------------------------------------------------------
    |
    | This option allows you to override the package's built-in route names.
    | This can help you avoid collisions with your existing route names.
    |
    */

    'route_names' => [
        'home' => env('SHOPIFY_ROUTE_NAME_HOME', 'home'),
        'authenticate' => env('SHOPIFY_ROUTE_NAME_AUTHENTICATE', 'authenticate'),
        'authenticate.token' => env('SHOPIFY_ROUTE_NAME_AUTHENTICATE_TOKEN', 'authenticate.token'),
        'billing' => env('SHOPIFY_ROUTE_NAME_BILLING', 'billing'),
        'billing.process' => env('SHOPIFY_ROUTE_NAME_BILLING_PROCESS', 'billing.process'),
        'billing.usage_charge' => env('SHOPIFY_ROUTE_NAME_BILLING_USAGE_CHARGE', 'billing.usage_charge'),
        'webhook' => env('SHOPIFY_ROUTE_NAME_WEBHOOK', 'webhook'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Shop auth guard
    |--------------------------------------------------------------------------
    |
    | This option allows you to override auth guard used by package middlewares
    |
    */
    'shop_auth_guard' => env('SHOPIFY_SHOP_AUTH_GUARD', null),

    /*
    |--------------------------------------------------------------------------
    | Shop auth provider
    |--------------------------------------------------------------------------
    |
    | This option allows you to override package's build-in auth model
    | If you need to keep User model intact, add custom auth provider and route middlewares for it
    |
    */
    'shop_auth_provider' => env('SHOPIFY_SHOP_AUTH_PROVIDER', 'shops'),

    /*
    |--------------------------------------------------------------------------
    | App Namespace
    |--------------------------------------------------------------------------
    |
    | This option allows you to set a namespace for the users in the DB.
    | Useful for running multiple apps using the same database instance.
    | Meaning, one shop can be part of many apps on the same database.
    |
    */

    'namespace' => env('SHOPIFY_APP_NAMESPACE', null),

    /*
    |--------------------------------------------------------------------------
    | Shopify Jobs Namespace
    |--------------------------------------------------------------------------
    |
    | This option allows you to change out the default job namespace
    | which is \App\Jobs. This option is mainly used if any custom
    | configuration is done in autoload and does not need to be changed
    | unless required.
    |
    */

    'job_namespace' => env('SHOPIFY_JOB_NAMESPACE', '\\App\\Jobs\\'),

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | This option allows you to set a prefix for URLs.
    | Useful for multiple apps using the same database instance.
    |
    */

    'prefix' => env('SHOPIFY_APP_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | AppBridge Mode
    |--------------------------------------------------------------------------
    |
    | AppBridge (embedded apps) are enabled by default. Set to false to use legacy
    | mode and host the app inside your own container.
    |
    */

    'appbridge_enabled' => (bool) env('SHOPIFY_APPBRIDGE_ENABLED', true),

    // Use semver range to link to a major or minor version number.
    // Leaving empty will use the latest version - not recommended in production.
    'appbridge_version' => env('SHOPIFY_APPBRIDGE_VERSION', 'latest'),

    // Set a new CDN URL if you want to host the AppBridge JS yourself or unpkg goes down.
    // DO NOT include a trailing slash.
    'appbridge_cdn_url' => env('SHOPIFY_APPBRIDGE_CDN_URL', 'https://unpkg.com'),

    /*
    |--------------------------------------------------------------------------
    | Shopify App Name
    |--------------------------------------------------------------------------
    |
    | This option simply lets you display your app's name.
    |
    */

    'app_name' => env('SHOPIFY_APP_NAME', 'Bit Integration'),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Version
    |--------------------------------------------------------------------------
    |
    | This option is for the app's API version string.
    | Use "YYYY-MM" or "unstable". Refer to Shopify documentation
    | at https://shopify.dev/api/usage/versioning#release-schedule
    | for the current stable version.
    |
    */

    'api_version' => env('SHOPIFY_API_VERSION', '2024-04'),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Key
    |--------------------------------------------------------------------------
    |
    | This option is for the app's API key.
    |
    */

    'api_key' => env('SHOPIFY_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Secret
    |--------------------------------------------------------------------------
    |
    | This option is for the app's API secret.
    |
    */

    'api_secret' => env('SHOPIFY_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Scopes
    |--------------------------------------------------------------------------
    |
    | This option is for the scopes your application needs in the API.
    |
    */

    'api_scopes' => env('SHOPIFY_API_SCOPES', 'read_products,read_themes'),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Grant Mode
    |--------------------------------------------------------------------------
    |
    | This option is for the grant mode when authenticating.
    | Default is "OFFLINE", "PERUSER" is available as well.
    | Note: Install will always be in offline mode.
    |
    */

    'api_grant_mode' => env('SHOPIFY_API_GRANT_MODE', 'OFFLINE'),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Redirect
    |--------------------------------------------------------------------------
    |
    | This option is for the redirect after authentication.
    |
    */

    'api_redirect' => env('SHOPIFY_API_REDIRECT', '/back/authenticate'),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Time Store
    |--------------------------------------------------------------------------
    |
    | This option is for the class which will hold the timestamps for
    | API calls.
    |
    */

    'api_time_store' => env('SHOPIFY_API_TIME_STORE', \Gnikyt\BasicShopifyAPI\Store\Memory::class),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Limit Store
    |--------------------------------------------------------------------------
    |
    | This option is for the class which will hold the call limits for REST
    | and GraphQL.
    |
    */

    'api_limit_store' => env('SHOPIFY_API_LIMIT_STORE', \Gnikyt\BasicShopifyAPI\Store\Memory::class),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Deferrer
    |--------------------------------------------------------------------------
    |
    | This option is for the class which will handle sleep deferrals for
    | API calls.
    |
    */

    'api_deferrer' => env('SHOPIFY_API_DEFERRER', \Gnikyt\BasicShopifyAPI\Deferrers\Sleep::class),

    /*
    |--------------------------------------------------------------------------
    | Shopify API Init Function
    |--------------------------------------------------------------------------
    |
    | This option is for initializing the BasicShopifyAPI package yourself.
    | The first param injected in is the current options.
    |    (\Gnikyt\BasicShopifyAPI\Options)
    | The second param injected in is the session (if available) .
    |    (\Gnikyt\BasicShopifyAPI\Session)
    | The third param injected in is the current request input/query array.
        (\Illuminate\Http\Request::all())
    | With all this, you can customize the options, change params, and more.
    |
    | Value for this option must be a callable (callable, Closure, etc).
    |
    */

    'api_init' => null,

    /*
    |--------------------------------------------------------------------------
    | Shopify "MyShopify" domain
    |--------------------------------------------------------------------------
    |
    | The internal URL used by shops. This will not change but in the future
    | it may.
    |
    */

    'myshopify_domain' => env('SHOPIFY_MYSHOPIFY_DOMAIN', 'myshopify.com'),

    /*
    |--------------------------------------------------------------------------
    | Enable Billing
    |--------------------------------------------------------------------------
    |
    | Enable billing component to the package.
    |
    */

    'billing_enabled' => (bool) env('SHOPIFY_BILLING_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Enable Freemium Mode
    |--------------------------------------------------------------------------
    |
    | Allow a shop use the app in "freemium" mode.
    | Shop will get a `freemium` flag on their record in the table.
    |
    */

    'billing_freemium_enabled' => (bool) env('SHOPIFY_BILLING_FREEMIUM_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Billing Redirect
    |--------------------------------------------------------------------------
    |
    | Required redirection URL for billing when
    | a customer accepts or declines the charge presented.
    |
    */

    'billing_redirect' => env('SHOPIFY_BILLING_REDIRECT', '/billing/process'),


    /*
    |--------------------------------------------------------------------------
    | Enable legacy support for features
    |--------------------------------------------------------------------------
    |
    */
    'app_legacy_supports' => [
        'after_authenticate_job' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Register listeners to the events
    |--------------------------------------------------------------------------
    |
    | In Laravel version 11 and later, event listeners located in the `App\Listeners`
    | directory are automatically registered by default. Therefore, manual registration
    | in this configuration file is unnecessary.
    |
    | If you register the listeners manually again here, the listener will be called twice.
    |
    | If you plan to store your listeners in a different directory like `App\Shopify\Listeners`
    | or within multiple directories, then you should register them here.
    |
    | If you are using Laravel version 10 or earlier, then corresponding listeners
    | must be registered here.
    |
    */

    'listen' => [
        \Osiset\ShopifyApp\Messaging\Events\AppInstalledEvent::class => [
            // \App\Listeners\MyListener::class,
        ],
        \Osiset\ShopifyApp\Messaging\Events\ShopAuthenticatedEvent::class => [
            // \App\Listeners\MyListener::class,
        ],
        \Osiset\ShopifyApp\Messaging\Events\ShopDeletedEvent::class => [
            // \App\Listeners\MyListener::class,
        ],
        \Osiset\ShopifyApp\Messaging\Events\AppUninstalledEvent::class => [
            // \App\Listeners\MyListener::class,
        ],
        \Osiset\ShopifyApp\Messaging\Events\PlanActivatedEvent::class => [
            // \App\Listeners\MyListener::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shopify Webhooks
    |--------------------------------------------------------------------------
    |
    | This option is for defining webhooks.
    | `topic` is the GraphQL value of the Shopify webhook event.
    | `address` is the endpoint to call.
    |
    | Valid values for `topic` can be found here:
    | https://shopify.dev/api/admin/graphql/reference/events/webhooksubscriptiontopic
    |
    */

    'webhooks' => [
        [
            'topic' => env('SHOPIFY_WEBHOOK_1_TOPIC', 'APP_UNINSTALLED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_1_ADDRESS', '/webhook/app-uninstalled')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_2_TOPIC', 'ORDERS_PAID'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_2_ADDRESS', '/webhook/orders-paid')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_3_TOPIC', 'CUSTOMERS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_3_ADDRESS', '/webhook/customers-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_4_TOPIC', 'ORDERS_UPDATED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_4_ADDRESS', '/webhook/orders-updated')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_5_TOPIC', 'BULK_OPERATIONS_FINISH'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_5_ADDRESS', '/webhook/bulk-operations-finish')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_6_TOPIC', 'PRODUCTS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_6_ADDRESS', '/webhook/products-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_7_TOPIC', 'ORDERS_FULFILLED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_7_ADDRESS', '/webhook/orders-fulfilled')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_8_TOPIC', 'ORDERS_DELETE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_8_ADDRESS', '/webhook/orders-delete')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_9_TOPIC', 'PRODUCTS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_9_ADDRESS', '/webhook/products-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_10_TOPIC', 'ORDER_TRANSACTIONS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_10_ADDRESS', '/webhook/order-transactions-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_11_TOPIC', 'CUSTOMERS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_11_ADDRESS', '/webhook/customers-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_12_TOPIC', 'ORDERS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_12_ADDRESS', '/webhook/orders-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_13_TOPIC', 'SHOP_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_13_ADDRESS', '/webhook/shop-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_14_TOPIC', 'THEMES_PUBLISH'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_14_ADDRESS', '/webhook/themes-publish')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_15_TOPIC', 'THEMES_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_15_ADDRESS', '/webhook/themes-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_16_TOPIC', 'PRODUCTS_DELETE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_16_ADDRESS', '/webhook/products-delete')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_17_TOPIC', 'CARTS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_17_ADDRESS', '/webhook/carts-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_18_TOPIC', 'CARTS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_18_ADDRESS', '/webhook/carts-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_19_TOPIC', 'CHECKOUTS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_19_ADDRESS', '/webhook/checkouts-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_20_TOPIC', 'CHECKOUTS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_20_ADDRESS', '/webhook/checkouts-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_21_TOPIC', 'COLLECTIONS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_21_ADDRESS', '/webhook/collections-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_22_TOPIC', 'COLLECTIONS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_22_ADDRESS', '/webhook/collections-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_23_TOPIC', 'CUSTOMERS_ENABLE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_23_ADDRESS', '/webhook/customers-enable')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_24_TOPIC', 'CUSTOMERS_DISABLE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_24_ADDRESS', '/webhook/customers-disable')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_25_TOPIC', 'CUSTOMERS_DELETE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_25_ADDRESS', '/webhook/customers-delete')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_26_TOPIC', 'CUSTOMER_GROUPS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_26_ADDRESS', '/webhook/customer-groups-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_27_TOPIC', 'DRAFT_ORDERS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_27_ADDRESS', '/webhook/draft-orders-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_28_TOPIC', 'DRAFT_ORDERS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_28_ADDRESS', '/webhook/draft-orders-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_29_TOPIC', 'FULFILLMENTS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_29_ADDRESS', '/webhook/fulfillments-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_30_TOPIC', 'FULFILLMENTS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_30_ADDRESS', '/webhook/fulfillments-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_31_TOPIC', 'FULFILLMENT_EVENTS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_31_ADDRESS', '/webhook/fulfillment-events-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_32_TOPIC', 'INVENTORY_ITEMS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_32_ADDRESS', '/webhook/inventory-items-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_33_TOPIC', 'INVENTORY_ITEMS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_33_ADDRESS', '/webhook/inventory-items-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_34_TOPIC', 'INVENTORY_LEVELS_CONNECT'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_34_ADDRESS', '/webhook/inventory-levels-connect')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_35_TOPIC', 'INVENTORY_LEVELS_DISCONNECT'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_35_ADDRESS', '/webhook/inventory-levels-disconnect')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_36_TOPIC', 'INVENTORY_LEVELS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_36_ADDRESS', '/webhook/inventory-levels-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_37_TOPIC', 'LOCATIONS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_37_ADDRESS', '/webhook/locations-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_38_TOPIC', 'LOCATIONS_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_38_ADDRESS', '/webhook/locations-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_39_TOPIC', 'ORDERS_CANCELLED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_39_ADDRESS', '/webhook/orders-cancelled')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_40_TOPIC', 'ORDERS_PARTIALLY_FULFILLED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_40_ADDRESS', '/webhook/orders-partially-fulfilled')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_41_TOPIC', 'REFUNDS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_41_ADDRESS', '/webhook/refunds-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_42_TOPIC', 'TENDER_TRANSACTIONS_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_42_ADDRESS', '/webhook/tender-transactions-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_43_TOPIC', 'THEMES_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_43_ADDRESS', '/webhook/themes-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_44_TOPIC', 'ORDERS_EDITED'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_44_ADDRESS', '/webhook/orders-edited')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_45_TOPIC', 'LOCALES_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_45_ADDRESS', '/webhook/locales-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_46_TOPIC', 'LOCALES_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_46_ADDRESS', '/webhook/locales-update')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_47_TOPIC', 'DISPUTES_CREATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_47_ADDRESS', '/webhook/disputes-create')),
        ],
        [
            'topic' => env('SHOPIFY_WEBHOOK_48_TOPIC', 'DISPUTES_UPDATE'),
            'address' => backend_url(env('SHOPIFY_WEBHOOK_48_ADDRESS', '/webhook/disputes-update')),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Shopify ScriptTags
    |--------------------------------------------------------------------------
    |
    | This option is for defining scripttags.
    |
    */

    'scripttags' => [
        /*
            [
                'src' => env('SHOPIFY_SCRIPTTAG_1_SRC', 'https://some-app.com/some-controller/js-method-response'),
                'event' => env('SHOPIFY_SCRIPTTAG_1_EVENT', 'onload'),
                'display_scope' => env('SHOPIFY_SCRIPTTAG_1_DISPLAY_SCOPE', 'online_store')
            ],
            ...
        */],

    /*
    |--------------------------------------------------------------------------
    | After Authenticate Job
    |--------------------------------------------------------------------------
    |
    | This option is for firing a job after a shop has been authenticated.
    | This, like webhooks and scripttag jobs, will fire every time a shop
    | authenticates, not just once.
    |
    |
    */

    /*
     * @deprecated This will be removed in the next major version.
     * @see
     */
    'after_authenticate_job' => [
        /*
            [
                'job' => env('AFTER_AUTHENTICATE_JOB'), // example: \App\Jobs\AfterAuthorizeJob::class
                'inline' => env('AFTER_AUTHENTICATE_JOB_INLINE', false) // False = dispatch job for later, true = dispatch immediately
            ],
        */],

    /*
    |--------------------------------------------------------------------------
    | Job Queues
    |--------------------------------------------------------------------------
    |
    | This option is for setting a specific job queue for webhooks, scripttags
    | and after_authenticate_job.
    |
    */

    'job_queues' => [
        'webhooks' => env('WEBHOOKS_JOB_QUEUE', null),
        'scripttags' => env('SCRIPTTAGS_JOB_QUEUE', null),
        'after_authenticate' => env('AFTER_AUTHENTICATE_JOB_QUEUE', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Config API Callback
    |--------------------------------------------------------------------------
    |
    | This option can be used to modify what returns when `getConfig('api_*')`
    | is used. A use-case for this is modifying the return of `api_secret`
    | or something similar.
    |
    | A closure/callable is required.
    | The first argument will be the key string.
    | The second argument will be something to help identify the shop.
    |
    */

    'config_api_callback' => null,

    /*
    |--------------------------------------------------------------------------
    | Enable Turbolinks or Hotwire Turbo
    |--------------------------------------------------------------------------
    |
    | If you use Turbolinks/Turbo and Livewire, turn on this setting to get
    | the token assigned automatically.
    |
    */

    'turbo_enabled' => (bool) env('SHOPIFY_TURBO_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Customize Models and Table Name
    |--------------------------------------------------------------------------
    |
    | You can customize you model and extend them
    | also you can customize tables name for charge and plan models.
    |
    */

    'models' => [
        /*
        * The fully qualified class name of the Charge model.
        */
        'charge' => App\Models\Charge::class,

        /*
        * The fully qualified class name of the Plan model.
        */
        'plan' => App\Models\Plan::class,
    ],

    'table_names' => [
        /*
        * The table name for Charge model.
        */
        'charges' => 'charges',

        /*
        * The table name for Plan model.
        */
        'plans' => 'plans',

        /*
         * The table name for the Shop.
         */
        'shops' => 'shops',
    ],

    /*
    |--------------------------------------------------------------------------
    | Checking theme compatibility
    |--------------------------------------------------------------------------
    |
    | It is necessary to check if your application is compatible with
    | the theme app blocks.
    |
    */

    'theme_support' => [
        /*
         * Specify the name of the template the app will integrate with
         */
        'templates' => ['product', 'collection', 'index'],
        /*
         * Interval for caching the request: minutes, seconds, hours, days, etc.
         */
        'cache_interval' => 'hours',
        /*
         * Cache duration
         */
        'cache_duration' => 12,
        /*
         * At which levels of theme support the use of "theme app extension" is not available
         * and script tags will be installed.
         * Available levels: FULL, PARTIAL, UNSUPPORTED.
         */
        'unacceptable_levels' => [
            Osiset\ShopifyApp\Objects\Enums\ThemeSupportLevel::UNSUPPORTED,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session token refresh
    |--------------------------------------------------------------------------
    |
    | For AppBridge, how often to refresh the session token for the user.
    |
    */

    'session_token_refresh_interval' => env('SESSION_TOKEN_REFRESH_INTERVAL', 2000),

    /*
    |--------------------------------------------------------------------------
    | Frontend engine used
    |--------------------------------------------------------------------------
    |
    | Available engines: "BLADE", "VUE", or "REACT".
    | For example, if you use React, you do not need to be redirected to a separate page to get the JWT token.
    | No changes are made for Vue.js and Blade.
    |
    */
    'frontend_engine' => env('SHOPIFY_FRONTEND_ENGINE', 'REACT'),

    'iframe_ancestors' => '',
];
