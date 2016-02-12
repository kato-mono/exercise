<?php

class Path
{
    const VENDOR_AUTOLOAD = '/var/www/html/public/tweet_storage/vendor/autoload.php';
    const APP_FILE_UTIL = '/var/www/html/public/tweet_storage/app/file_util.php';

    // config
    const CONFIG_TWITTER_CONNECTION = '/var/www/html/public/tweet_storage/config/twitter/connection.json';
    const CONFIG_AWS_CONNECTION = '/var/www/html/public/tweet_storage/config/aws/connection.json';

    // url
    const URL_TWITTER_FETCH_BEARER_TOKEN = 'https://api.twitter.com/oauth2/token';
    const URL_TWITTER_FETCH_TIMELINE = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
}
