<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterApiRequest;
use App\Http\Requests\TokenGenerateApiRequest;
use App\Models\User;
use App\Traits\CustomResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    use CustomResponseTrait;

    public function register(RegisterApiRequest $request)
    {
        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'type'      => 4,
                'is_active' => 1,
                'password'  => bcrypt($request->password),
            ]);

            return $this->jsonResponse(
                flag: true,
                message: "Success",
                data: [
                    'name'  => $user->name,
                    'email' => $user->email
                ],
                responseCode: HttpResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // report($e);

            return $this->jsonResponse(
                message: $e->getMessage(),
                responseCode: $e->getCode()
            );
        }
    }

    public function issueToken(TokenGenerateApiRequest $request)
    {
        $response = Http::asForm()->post(config('app.url') . '/oauth-admin-app/token', [
            'grant_type'    => 'password',
            'client_id'     => $request->header('X-Client-Id'),
            'client_secret' => $request->header('X-Client-Secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '',
        ]);

        // handle response
        if ($response->successful()) {
            return $this->jsonResponse(
                flag: true,
                message: "Success",
                data: [],
                extra: $response->json(),
                responseCode: HttpResponse::HTTP_OK
            );
        }

        // If request failed, decode JSON error
        return $this->jsonResponse(
            message: $response->json('error_description', 'Invalid credentials'),
            responseCode: 401,
        );
    }

    public function refresh(Request $request)
    {
        $request->validate(
            ['refresh_token' => 'required'],
            ['refresh_token.required' => 'Refresh Token is required.']
        );

        $response = Http::asForm()->post(config('app.url') . '/oauth-admin-app/token', [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id'     => $request->header('X-Client-Id'),
            'client_secret' => $request->header('X-Client-Secret'),
            'scope'         => '',
        ]);

        if ($response->successful()) {
            return $this->jsonResponse(
                flag: true,
                message: "Success",
                data: [],
                extra: $response->json(),
                responseCode: HttpResponse::HTTP_OK
            );
        }

        // If request failed, decode JSON error
        return $this->jsonResponse(
            message: $response->json('error_description', 'Invalid refresh token'),
            responseCode: 401,
        );
    }
}
