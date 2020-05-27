<?php
    require_once 'vendor/autoload.php';

    //contoh penggunaan
    /* $resp = odoo_api('GET', 'https://dev.berdikari-persero.com/api/search_read/sale.order', 'admin', 'admin');
    header('Content-Type: application/json');
    print_r($resp); */

    function odoo_api($method, $url, $username, $password)
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '31YQM64hD3BYeqIfJjSaxbEQ0YXwFB',
            'clientSecret'            => 'h4XxZlkzjzQNbwHHkbX0EMJG9PBR65',
            'redirectUri'             => 'https://dev.berdikari-persero.com',
            'urlAuthorize'            => 'https://dev.berdikari-persero.com/api/authentication/oauth2/authorize',
            'urlAccessToken'          => 'https://dev.berdikari-persero.com/api/authentication/oauth2/token',
            'urlResourceOwnerDetails' => ''
        ]);

        try {
            $accessToken = $provider->getAccessToken('password', [
                'username' => $username,
                'password' => $password      
            ]);
            $request = $provider->getAuthenticatedRequest(
                $method,
                $url,
                $accessToken
            );

            $client = new \GuzzleHttp\Client();
            $response = $client->send($request);
            $rawBody = $response->getBody()->getContents();
            
            return $rawBody;
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            exit($e->getMessage());
        }
    }
?>