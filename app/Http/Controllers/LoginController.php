<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * ログインコントローラー
 *
 * @package App\Http\Controllers
 * @author naito
 * @version ver1.0.0 2024/03/03
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // 認可コードの取得
        $authorizationCode = $request->input('code');
        $responseAuthCode = Http::withHeaders([
            'Authorization' => 'Basic {' . base64_encode(env('SMAREGI_CLIENT_ID') . ':' . env('SMAREGI_CLIENT_SECRET')) . '}',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://id.smaregi.dev/authorize/token', [
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'redirect_uri' => env('APP_URL') . env('SMAREGI_API_URL') . env('SMAREGI_REDIRECT_URL'),
        ]);

        // ユーザー情報の取得
        $authCode = json_decode($responseAuthCode->getBody()->getContents(), true);
        //dd($authCode);
        $responseUser = Http::withHeaders([
            'Authorization' => $authCode['access_token'],
        ])->post('https://id.smaregi.dev/userinfo');
        //$user = json_decode($responseUser->getBody()->getContents(), true);

        return view(
            'menu',
            [
                'message' => $responseUser,
            ]
        );
    }

    /**
     * ユーザー認可の要求
     *
     * @return string リダイレクト
     */
    public function requestUserAuthorization(): string
    {
        return redirect(
            'https://id.smaregi.dev/authorize?response_type=code&client_id=' . env('SMAREGI_CLIENT_ID') .
                '&scope=openid+email+profile+offline_access&state=' . rand() .
                '&redirect_uri=' . env('APP_URL') . env('SMAREGI_API_URL') . env('SMAREGI_REDIRECT_URL')
        );
    }
}
