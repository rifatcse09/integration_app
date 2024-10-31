<?php

declare(strict_types=1);

use App\Enums\PlanFeature;
use App\Enums\Popup\PopupType;
use Carbon\Carbon;
use App\Models\Shop;
use App\Services\PlanFeatureService;
use App\Utilities\ApiJsonResponse;
use App\Utilities\UrlManager;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

if (!function_exists('shop')) {

    /**
     * @param $guard
     * @return Shop|null
     */
    function shop($guard = null): ?Shop
    {
        /**
         * @var Shop $shop
         */
        $shop = auth($guard)->user();

        return $shop;
    }
}


if (!function_exists('admin_api')) {
    function admin_api(): string
    {
        return '/admin/api/' . env('SHOPIFY_API_VERSION');
    }
}

if (!function_exists('carbon')) {
    /**
     * @param string|null $date
     * @param string $timezone
     * @return Carbon
     */
    function carbon(string $date = null, string $timezone = 'UTC'): Carbon
    {
        if (!$date) {
            return Carbon::now($timezone);
        }

        return (new Carbon($date, $timezone));
    }
}

if (!function_exists('str_unique')) {
    /**
     * @param int $length
     * @return string
     */
    function str_unique(int $length = 16): string
    {
        $side = rand(0, 1); // 0 = left, 1 = right
        $salt = rand(0, 9);
        $len = $length - 1;
        $string = Str::random($len <= 0 ? 7 : $len);

        $separatorPos = (int) ceil($length / 4);

        $string = $side === 0 ? ($salt . $string) : ($string . $salt);
        $string = substr_replace($string, '-', $separatorPos, 0);

        return substr_replace($string, '-', negative_value($separatorPos), 0);
    }
}



if (!function_exists('negative_value')) {
    /**
     * @param int|float $value
     * @param bool $float
     * @return int|float
     */
    function negative_value(int|float $value, bool $float = false): int|float
    {
        if ($float) {
            $value = (float) $value;
        }

        return 0 - abs($value);
    }
}


if (!function_exists('api')) {
    /**
     * @param array|Arrayable|string|null $data
     * @return ApiJsonResponse
     */
    function api(mixed $data = []): ApiJsonResponse
    {
        return new ApiJsonResponse($data);
    }
}

if (!function_exists('recursive_merge')) {
    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    function recursive_merge(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = recursive_merge($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }
}

if (!function_exists('extractKeysWithValue')) {
    /**
     * @param array $array
     * @return array
     */
    function extractKeysWithValue($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $nestedKeys = extractKeysWithValue($value);
                    if (!empty($nestedKeys)) {
                        $result[$key] = $nestedKeys;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
}

if (!function_exists('extractMatchingKeys')) {
    /**
     * Extracts key-value pairs from the second array where keys and value types match those in the first array.
     *
     * @param array $sourceArray The array with the original keys and value types.
     * @param array $targetArray The array from which to extract the matching key-value pairs.
     * @return array An array containing the key-value pairs from $targetArray that match the keys and value types in $sourceArray.
     */
    function extractMatchingKeys(array $sourceArray, array $targetArray): array
    {
        $result = [];

        $flattenSourceArray = Arr::dot($sourceArray);

        foreach ($flattenSourceArray as $key => $value) {
            if (!Arr::has($targetArray, $key)) continue;

            $valueInTargetArray = Arr::get($targetArray, $key);
            if (gettype($value) != gettype($valueInTargetArray)) continue;

            if (is_array($valueInTargetArray)) {
                if (!Arr::isAssoc($valueInTargetArray)) {
                    Arr::set($result, $key, $valueInTargetArray);
                }
            } else {
                Arr::set($result, $key, $valueInTargetArray);
            }
        }
        return $result;
    }
}




if (!function_exists('replaceValuesByPopupType')) {
    /**
     * @param array $array1
     * @param array $array1
     * @return array
     */
    function replaceValuesByPopupType($array1, $array2)
    {
        $popupTypeValues = [];
        foreach ($array2 as $item) {
            $popupType = $item['popup_type'];
            $popupTypeValues[$popupType] = $item;
        }

        return array_map(function ($item) use ($popupTypeValues) {
            $popupType = $item['popup_type'];
            if (isset($popupTypeValues[$popupType])) {
                return array_replace($item, $popupTypeValues[$popupType]);
            }
            return $item;
        }, $array1);
    }
}

if (!function_exists('formatedDateFilter')) {
    /**
     * @param array $filter
     * @return array
     */
    function formatedDateFilter(array $filter): array
    {
        if (!Arr::has($filter, 'from') || !Arr::has($filter, 'to')) {
            $from =  Carbon::now()->subDays(6);
            $to =  Carbon::now();
        } else {
            $from = Carbon::parse($filter['from']);
            $to = Carbon::parse($filter['to']);

            // Check if $from is greater than or equal to $to
            if ($from->greaterThanOrEqualTo($to)) {
                // Swap $from and $to
                $temp = $from;
                $from = $to;
                $to = $temp;
            }
        }

        return [
            'from' => $from->startOfDay(),
            'to' => $to->endOfDay(),
        ];
    }
}

if (!function_exists('daysRange')) {
    /**
     * @return array
     */
    function daysRange($fromDate, $toDate): array
    {
        $fromDate = $fromDate instanceof Carbon ? $fromDate->format('Y-m-d') : $fromDate;
        $toDate = $toDate instanceof Carbon ? $toDate->format('Y-m-d') : $toDate;
        $days = [];
        $startDate = new DateTime($fromDate);
        $endDate = new DateTime($toDate);

        $period = new DatePeriod(
            $startDate,
            new DateInterval('P1D'),
            $endDate
        );

        foreach ($period as $value) {
            $days[] = $value->format('Y-m-d');
        }

        return $days;
    }
}


if (!function_exists('json_parse')) {
    /**
     * @param string $data
     * @param bool $exception
     * @return array
     * @throws Exception
     */
    function json_parse(string $data, bool $exception = true): array
    {
        $data = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            if ($exception) {
                throw new \Exception('Invalid JSON, Failed to parse!');
            }
            $data = [];
        }

        return $data;
    }
}


if (!function_exists('url_concat')) {
    function url_concat(...$uris): string
    {
        /**
         * @var UrlManager $urlManager
         */
        $urlManager = app(UrlManager::class);

        foreach ($uris as $uri) {
            if (blank($uri)) {
                continue;
            }

            $urlManager->concat($uri);
        }

        $uri = $urlManager->getUrl();
        $urlManager->setBaseUrl('');

        return $uri;
    }
}

if (!function_exists('backend_url')) {
    /**
     * @param $uri
     * @return string
     */
    function backend_url($uri): string
    {
        return url_concat(env('APP_URL', 'http://127.0.0.1:8006'), env('ROUTE_BACKEND_PREFIX', '/back'), $uri);
    }
}


if (!function_exists('app_url')) {
    function app_url($uri): string
    {
        return url_concat(env('APP_URL', 'http://localhost:8008'), $uri);
    }
}

if (!function_exists('from_gid')) {
    /**
     * @param $gid
     * @props int $id
     * @props string $type
     * @return ?object
     */
    function from_gid($gid): ?object
    {
        if (!str_contains($gid, 'gid://')) {
            return null;
        }

        $data = [];

        $extractedData = explode('/', substr($gid, strpos($gid, 'gid') + 6));

        $data['node'] = $extractedData[1];
        $data['id'] = (int) $extractedData[2];

        return (object) $data;
    }
}

if (!function_exists('load_data')) {
    /**
     * @param string $path
     * @param array $data
     * @return array
     * @throws Exception
     */
    function load_data(string $path, array $data = []): array
    {
        extract($data, EXTR_SKIP);
        $path = 'data/' . str_replace('.', '/', $path) . '.data.php';
        $file = resource_path($path);

        if (!file_exists($file)) {
            throw new \Exception("Data file not found");
        }

        return require $file;
    }
}


if (!function_exists('slug')) {
    /**
     * @param string|null $string
     * @return string
     */
    function slug(string $string = null): string
    {
        return Str::slug($string);
    }
}


if (!function_exists('graphqlQueryByField')) {
    /**
     * @param string $field
     * @param array $values
     * @param bool $include
     * @return string
     */
    function graphqlQueryByField(string $field, array $values = [], bool $include = true): string
    {
        $query = "";
        if (count($values) > 0) {
            $query = implode(' OR ', array_map(function ($value) use ($field) {
                return "({$field}:{$value})";
            }, $values));

            if (!$include) {
                $query = "NOT ({$query})";
            }
        }

        return $query;
    }
}


if (!function_exists('load_graphql_array_schema')) {
    /**
     * @param string $path
     * @param array $data
     * @return array
     * @throws Exception
     */
    function load_graphql_array_schema(string $path, array $data = []): array
    {
        extract($data, EXTR_SKIP);
        $path = 'graph/' . str_replace('.', '/', $path) . '.array.php';
        $path = resource_path('views/' . $path);

        if (!file_exists($path)) {
            throw new \Exception("File not found for tcp response");
        }

        return require $path;
    }
}

if (!function_exists('load_graphql_blade_schema')) {
    /**
     * @param string $path
     * @param array $data
     * @return string
     * @throws Exception
     */
    function load_graphql_blade_schema(string $path, array $data = []): string
    {
        $location = resource_path('views/graph/' . str_replace('.', '/', $path) . '.blade.php');

        if (!file_exists($location)) {
            throw new \Exception("File not found for tcp response");
        }

        return view('graph.' . $path, $data)->render();
    }
}



// if (!function_exists('feature_allows')) {
//     /**
//      * @param PlanFeature $feature
//      * @param Shop|null $shop
//      * @return void
//      * @throws FeatureNotAllowedException
//      */
//     function feature_allows(PlanFeature $feature, ?Shop $shop = null): void
//     {
//         /**
//          * @var PlanFeatureService $featureFlag
//          */
//         $featureFlag = app(PlanFeatureService::class);

//         $featureFlag->allows($feature, $shop);
//     }
// }

// if (!function_exists('feature_enabled')) {
//     /**
//      * @param PlanFeature $feature
//      * @param ?Shop $shop
//      * @return bool
//      */
//     function feature_enabled(PlanFeature $feature, ?Shop $shop = null): bool
//     {
//         /**
//          * @var PlanFeatureService $featureFlag
//          */
//         $featureFlag = app(PlanFeatureService::class);

//         return $featureFlag->hasEnabled($feature, $shop);
//     }
// }

// if (!function_exists('feature_get')) {
//     /**
//      * @param PlanFeature $feature
//      * @param Shop|null $shop
//      * @return mixed
//      */
//     function feature_get(PlanFeature $feature, ?Shop $shop = null): mixed
//     {
//         /**
//          * @var PlanFeatureService $featureFlag
//          */
//         $featureFlag = app(PlanFeatureService::class);

//         return $featureFlag->getFlagValue($feature, $shop);
//     }
// }

if (!function_exists('url_replacer')) {

    /**
     * @param string $url
     * @param array $attr
     * @return string
     */
    function url_replacer(string $url, array $attr): string
    {
        if (array_is_list($attr)) {
            return $url;
        }

        $replacer = [];

        foreach ($attr as $key => $value) {
            $replacer['{' . strtolower($key) . '}'] = $value;
        }

        return strtr($url, $replacer);
    }
}



if (!function_exists('sanitizeAndCapitalizeName')) {
    /**
     * @param string $text
     * @return string
     */
    function sanitizeAndCapitalizeName(string $text): string
    {
        return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
    }
}

if (!function_exists('encryptAndEncode')) {
    /**
     * Encrypts JSON data and encodes it to Base64.
     *
     * @param array $data
     * @return string|null
     */
    function encryptAndEncode(array $data)
    {
        $jsonData = json_encode($data);
        $encryptedData = Crypt::encryptString($jsonData);
        return base64_encode($encryptedData);
    }
}

if (!function_exists('decodeAndDecrypt')) {
    /**
     * Decodes Base64 encoded data and decrypts it to retrieve JSON data.
     *
     * @param string $encodedData
     * @return array|null
     */
    function decodeAndDecrypt(string $encodedData)
    {
        $encryptedData = base64_decode($encodedData);
        $jsonData = Crypt::decryptString($encryptedData);
        return json_decode($jsonData, true);
    }
}
if (!function_exists('generateTaskUrl')) {

    function generateTaskUrl($shop, $token)
    {
        list($subdomain) = explode('.', $shop->name);
        $host = base64_encode('admin.shopify.com/store/' . $subdomain);

        $url = '/task-topic/4X0a-825UsrHV-c0tL' . '/?shop=' . urlencode($shop->name) . '&host=' . urlencode($host) . '&token='. $token;

        return url($url);
    }
}

if (!function_exists('snakeToTitleCase')) {

    function snakeToTitleCase($string): string
    {
        $parts = explode('.', $string);
        $convertedParts = array_map(function($part) {
            return ucwords(str_replace('_', ' ', $part));
        }, $parts);
        return implode(' ', $convertedParts);
    }
}

if (!function_exists('getIdByUid')) {

// Helper method to fetch the ID by UID
    function getIdByUid(string $uid, string $model): int
    {
        return $model::where('uid', $uid)->value('id');
    }
}

if (!function_exists('isValidDateString')) {

    function isValidDateString($input): bool
    {
        $timestamp = strtotime($input);
        return $timestamp !== false;
    }
}
if (!function_exists('parseResponse')) {
    function parseResponse($response): array
    {
        if ($response === null) {
            return [];
        }

        return json_decode(json_encode($response), true);
    }
}



