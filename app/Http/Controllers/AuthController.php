<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;


class AuthController extends Controller
{

    public function getToken(ServerRequestInterface $request) {

        $validate = validator($request->getParsedBody(), [
            'username' => 'required',
            'password' => 'required',
            'redirect_uri' => 'required',
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $body = $request->getParsedBody();
        unset($body['_token']);
        unset($body['redirect_uri']);


        $updatedRequest = $request->withParsedBody(array_merge($body, [
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'grant_type' => 'password',
        ]));


        $passportTokenController = new \Laravel\Passport\Http\Controllers\AccessTokenController(
            app(\League\OAuth2\Server\AuthorizationServer::class),
            app(\Laravel\Passport\TokenRepository::class)
        );


        try {
            $response = $passportTokenController->issueToken($updatedRequest);


            return redirect()->to($request->getParsedBody()['redirect_uri'])
                ->cookie(
                    'access_token',
                    json_decode((string) $response->getContent(), true)['access_token'],
                    json_decode((string) $response->getContent(), true)['expires_in'] / 60,
                    '/',
                    '.mgpapelaria.com.br',
                    true,
                    false,
                );
        } catch (Exception $e) {
            return redirect('/login?redirect_uri=' . $request->getParsedBody()['redirect_uri'] . '&error=' . true);
        }
    }

    public function getTokenJson(ServerRequestInterface $request) {
        $validate = validator($request->getParsedBody(), [
            'grant_type' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
    
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
    
    
        $passportTokenController = new \Laravel\Passport\Http\Controllers\AccessTokenController(
            app(\League\OAuth2\Server\AuthorizationServer::class),
            app(\Laravel\Passport\TokenRepository::class)
        );
    
    
        try {
            $response = $passportTokenController->issueToken($request);
    
    
            return response()->json(json_decode((string) $response->getContent(), true))->cookie(
                'access_token',
                json_decode((string) $response->getContent(), true)['access_token'],
                json_decode((string) $response->getContent(), true)['expires_in'] / 60,
                '/',
                '.mgpapelaria.com.br',
                true,
                false,
            );
    
        } catch (Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json(['message' => 'Invalid request. Please enter a username or a password.'], 400);
            } elseif ($e->getCode() === 401) {
                return response()->json(['message' => 'Your credentials are incorrect. Please try again'], 401);
            }
            return response()->json(['message' => 'Something went wrong on the server.'], 500);
        }
    }

    public function refreshToken(ServerRequestInterface $request) {
        $validate = validator($request->getParsedBody(), [
            'refresh_token' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'grant_type' => 'required',
        ]);
    
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
    
    
        $passportTokenController = new \Laravel\Passport\Http\Controllers\AccessTokenController(
            app(\League\OAuth2\Server\AuthorizationServer::class),
            app(\Laravel\Passport\TokenRepository::class)
        );
    
        try {
            $response = $passportTokenController->issueToken($request);
    
    
            return response()->json(json_decode((string) $response->getContent(), true))->cookie(
                'access_token',
                json_decode((string) $response->getContent(), true)['access_token'],
                json_decode((string) $response->getContent(), true)['expires_in'] / 60,
                '/',
                '.mgpapelaria.com.br',
                true,
                false,
            );
    
        } catch (Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json(['message' => 'Invalid request. Please enter a username or a password.'], 400);
            } elseif ($e->getCode() === 401) {
                return response()->json(['message' => 'Your credentials are incorrect. Please try again'], 401);
            }
            return response()->json(['message' => 'Something went wrong on the server.'], 500);
        }
    }

    public function checkToken(Request $request) {
        $expires_in = $request->user()->token()->expires_at->diffInSeconds(now());
    
        $data = [
            'message' => 'Token is valid',
            // 'access_token' => $request->bearerToken(),
            'expires_in' => $expires_in,
            'user_id' => $request->user()->codusuario,
            'usuario' => $request->user()->usuario,
        ];
    
        return response()->json($data, 200);
    }

    public function logout(Request $request) {
        // revoke tokens
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
    
        return response()->json('Logged out successfully', 200);
    }

}
