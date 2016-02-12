<?php

require_once './path.php';
require_once Path::VENDOR_AUTOLOAD;
require_once Path::APP_FILE_UTIL;

class Teller
{
    // twitter connection settings
    private $api_key;
    private $api_secret;
    private $bearer_token;

    public function __construct()
    {
        $twitter_configs_file_path = Path::CONFIG_TWITTER_CONNECTION;
        $twitter_configs = File_Util::load_json_config($twitter_configs_file_path);

        // (api_key, api_secret)、別名(consumer_key, consumer_secret)
        $this->api_key = $twitter_configs['consumer_key'];
        $this->api_secret = $twitter_configs['consumer_secret'];

        $this->fetch_twitter_bearer_token();
    }

    public function fetch_10_tweets()
    {
        $request_url = Path::URL_TWITTER_FETCH_TIMELINE;

        $fetch_condition = [
            'screen_name' => '@ka_ko_mo_no',
            'count' => 10,
        ];

        if ($fetch_condition) {
            $request_url .= '?'.http_build_query($fetch_condition);
        }

        $context = $this->make_bearer_context_get();

        return file_get_contents(
            $request_url,
            false,
            $context
        );
    }

    private function fetch_twitter_bearer_token()
    {
        $credential = base64_encode($this->api_key.':'.$this->api_secret);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Authorization: Basic '.$credential,
                    'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                ],
                'content' => http_build_query(['grant_type' => 'client_credentials']),
            ],
        ]);

        $request_url = Path::URL_TWITTER_FETCH_BEARER_TOKEN;
        $response_json = file_get_contents(
            $request_url,
            false,
            $context
        );

        $this->bearer_token = json_decode($response_json)->access_token;
    }

    private function make_bearer_context_get()
    {
        return stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Authorization: Bearer '.$this->bearer_token,
                ],
            ],
        ]);
    }
}
