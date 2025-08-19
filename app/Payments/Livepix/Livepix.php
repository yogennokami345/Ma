<?php

namespace App\Payments\Livepix;

use App\Utils\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class Livepix
{
    protected $urlBase;
    protected $redirect;
    protected $client_id;
    protected $client_secret;

    public function __construct()
    {
        $appurl = env('APP_URL');
        $this->urlBase = 'https://api.livepix.gg/v2';
        $this->redirect = "{$appurl}/callback/pay";
        $this->client_id = Settings::find('app.app_livepix_client_id', null);
        $this->client_secret = Settings::find('app.app_livepix_client_secret', null);
    }

    private function login()
    {
        $response = Http::asForm()->post('https://oauth.livepix.gg/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'scope' => 'account:read wallet:read webhooks payments:write payments:read',
        ]);

        if ($response->status() === 200) {
            $data = $response->json();
            $accessToken = $data['access_token'];
            $expiresIn = $data['expires_in'];

            cache()->put('livepix_access_token', $accessToken, now()->addSeconds($expiresIn));

            return $accessToken;
        }

        throw new HttpResponseException(response()->json([
            'error' => 'Falha ao realizar login',
            'status' => $response->status(),
            'details' => $response->json(),
        ], $response->status()));
    }

    private function getAccessToken()
    {
        $accessToken = cache()->get('livepix_access_token');

        if (!$accessToken) {
            $accessToken = $this->login();
        }

        return $accessToken;
    }

    public function createPayment($amount, $order_id)
    {
        $accessToken = $this->getAccessToken();
        Log::info([
            'amount' => (int) ($amount * 100),
            'currency' => 'BRL',
            'redirectUrl' => "{$this->redirect}/{$order_id}",
        ]);
        $response = Http::withToken($accessToken)->post($this->urlBase . '/payments', [
            'amount' => (int) ($amount * 100),
            'currency' => 'BRL',
            'redirectUrl' => "{$this->redirect}/{$order_id}",
        ]);

        if ($response->status() === 201) {
            return $response->json()['data'];
        }

        if ($response->status() === 401) {
            $accessToken = $this->login();
            return $this->createPayment($amount, $order_id);
        }

        throw new HttpResponseException(response()->json([
            'error' => 'Falha ao gerar pagamento',
            'status' => $response->status(),
            'details' => $response->json(),
        ], $response->status()));
    }

    public function consultPayment($paymentId)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->get($this->urlBase . '/payments/' . $paymentId);

        if ($response->status() === 200) {
            return $response->json()['data'];
        }

        if ($response->status() === 401) {
            $accessToken = $this->login();
            return $this->consultPayment($paymentId);
        }

        Log::error('Erro ao consultar pagamento', [
            'paymentId' => $paymentId,
            'status' => $response->status(),
            'details' => $response->json(),
        ]);

        throw new HttpResponseException(response()->json([
            'error' => 'Falha ao consultar pagamento',
            'status' => $response->status(),
            'details' => $response->json(),
        ], $response->status()));
    }
}
