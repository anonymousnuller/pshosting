<?php

$api = new API();
class API extends Controller
{

    public function validateKey($api_key)
    {
        if($api_key == env('GLOBAL_API_KEY')){
            return true;
        }

        return false;
    }

    public function getClient() : \GuzzleHttp\Client {
        return new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => env('GLOBAL_API_KEY')
            ]
        ]);
    }

    public function setDiscord($support_pin, $discord_id) {
        $client = $this->getClient();
        $response = $client->post(
            'https://portal.german-host.io/api/v1/discord/setDiscordID/',

            [
                'form_params' => [
                    'support_pin' => $support_pin,
                    'discord_id' => $discord_id
                ]
            ],
        );

        return json_decode((string) $response->getBody());
    }

    public function getAlert() {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://status.black-host.eu/api/v1/alerts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;


    }

    // validate production key
    public function validateLiveKey($authToken) {
        $SQL = self::db()->prepare("SELECT * FROM `api_keys` WHERE `api_key` = :api_key AND `state` = :state AND `type` = :type");
        $SQL->execute(array(":state" => 'active', ":type" => 'production', ":api_key" => $authToken));

        if ($SQL->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    // validate production key
    public function validateSandboxKey($authToken) {
        $SQL = self::db()->prepare("SELECT * FROM `api_keys` WHERE `api_key` = :api_key AND `state` = :state AND `type` = :type");
        $SQL->execute(array(":state" => 'active', ":type" => 'sandbox', ":api_key" => $authToken));

        if ($SQL->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    // get last use
    public function getLastuse($apikey, $data) {
        $SQL = self::db()->prepare("SELECT * FROM `api_logs` WHERE `api_key` = :key");
        $SQL->execute(array(":key" => $apikey));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response[$data];
    }

    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        return $headers;
    }
    /**
     * get access token from header
     * */
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }



}