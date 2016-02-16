<?php

namespace Monosense\Exercise;

require_once './Path.php';
require_once Path::VENDOR_AUTOLOAD;
require_once Path::APP_FILE_UTIL;

class TwitterMediator
{
    // twitter connection settings
    private $api_key;
    private $api_secret;
    private $bearer_token;
    private $screen_name;

    public function __construct($user_name)
    {
        $twitter_configs_file_path = Path::CONFIG_TWITTER_CONNECTION;
        $twitter_configs = FileUtil::loadJsonConfig($twitter_configs_file_path);

        // (api_key, api_secret)、別名(consumer_key, consumer_secret)
        $this->api_key = $twitter_configs['consumer_key'];
        $this->api_secret = $twitter_configs['consumer_secret'];
        $this->screen_name = $user_name;

        $this->fetchTwitterBearerToken();
    }

    /**
     * 最新10件のツイートを取得する.
     *
     * @return string JSON
     */
    public function fetch10Tweets()
    {
        $request_url = Path::URL_TWITTER_FETCH_TIMELINE;

        $fetch_condition = [
            'screen_name' => $this->screen_name,
            'count' => 10,
            'trim_user' => true,
        ];

        if ($fetch_condition) {
            $request_url .= '?'.http_build_query($fetch_condition);
        }

        $context = $this->makeBearerContextGet();

        $response = file_get_contents(
            $request_url,
            false,
            $context
        );

        return $this->formatTwitterResponse($response);
    }

    /**
     * ベアラートークンを取得する.
     *
     * @return string
     */
    private function fetchTwitterBearerToken()
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

    /**
     * twitterAPIから返されるレスポンスを逆順、かつ、idをキーに配置した状態に整形する.
     * (idをキーに配置するのは後々マージなどしやすくするため).
     *
     * @param string $response JSON
     *
     * @return string JSON
     */
    private function formatTwitterResponse($response)
    {
        // 最新のツイートが頭となるので逆順にする
        $reverse_tweets = array_reverse(json_decode($response, true));

        $formated_reverse_tweets = [];
        foreach ($reverse_tweets as $tweet) {
            $formated_reverse_tweets[$tweet['id']] = $tweet;
        }

        return json_encode($formated_reverse_tweets);
    }

    /**
     * ベアラートークンによるGET通信用のcontextを生成する.
     *
     * @return array
     */
    private function makeBearerContextGet()
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
