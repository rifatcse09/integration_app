<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppCredentialResource;
use App\Models\App;
use App\Services\AppService;
use App\Services\CredentialService;
use App\Services\Factory\CredentialFactory;
use App\Services\Factory\ServiceFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class CredentialController extends Controller
{
    public function __construct(protected CredentialService $credentialService, protected AppService $appService) {

    }

    /**
     * Retrieves the list of supported authentication methods for the given app.
     *
     * This method fetches the supported authentication methods for a specific app
     * and also retrieves the app's details along with any active credentials.
     *
     * @param App $app
     * @return JsonResponse
     * @throws Exception
     */
    public function getSupportedAuthMethods(App $app): JsonResponse
    {
        $authList = CredentialFactory::make($app->pointer)->getSupportedAuthMethods();

        $app = $this->appService->findAppWithActiveCredByUid($app->uid);

        $appResource = new AppCredentialResource($app);

        return api(['auth_info' => ['auth_list' => $authList, 'app' => $appResource]])
            ->success('Auth Info');
    }

    /**
     * Handles the selection of an authentication method.
     *
     * This method processes the request to select an authentication type,
     * retrieves the corresponding service, and handles the authentication logic.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|\Exception If the 'auth_type' is not present in the request.
     */
    public function handleAuthSelection(Request $request): JsonResponse
    {
        if (!$request->has('auth_type')) {
            throw new Exception('Select auth type');
        }

        $response = $this->credentialService->handleAuthSelection($request->all());

        return api($response)
            ->success('Auth Processing');
    }
    /**
     * Handles the OAuth callback for the specified service.
     *
     * This method is called when an OAuth authentication flow is completed.
     * It delegates the handling of the callback to the appropriate service based on the service name.
     * The resulting URL is then used to redirect the user.
     *
     * @param Request $request
     * @param string $serviceName The name of the service for which the OAuth callback is being handled.
     * @return RedirectResponse A redirect response to the URL provided by the service.
     * @throws Exception
     */
    public function callback(Request $request, string $serviceName)
    {
        $result = $this->credentialService->handleAuthCallback($request->all());
        $url = $result['url'];
        return redirect($url);

    }

    public function delete(string $uid): JsonResponse
    {
        $result = $this->credentialService->softDeleteByUid($uid);

        if ($result) {
            return api()
                ->success('Credential soft deleted successfully');
        } else {
            return api()
                ->fails('Failed to soft delete credential', 500);
        }
    }


}
