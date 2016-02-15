<?php

namespace Monosense\Exercise;

require_once './Path.php';
require_once Path::VENDOR_AUTOLOAD;
require_once Path::APP_FILE_UTIL;
require_once Path::APP_TWITTER_MEDIATOR;

use Aws\S3\S3Client;

class S3Mediator
{
    // aws connection settings
    private $s3_client;
    private $bucket;
    private $store_directory; // スラッシュを含んだディレクトリ名「directory_name/」

    const S3_FOLDER_DELIMITER = '/';
    const FILE_EXTENTION = '.json';

    public function __construct()
    {
        $aws_configs_file_path = Path::CONFIG_AWS_CONNECTION;
        $aws_configs = FileUtil::loadJsonConfig($aws_configs_file_path);

        $this->s3_client = S3Client::factory(
            [
                'credentials' => $aws_configs['credentials'],
                'region' => 'ap-northeast-1',
                'version' => 'latest',
            ]
        );

        $this->bucket = $aws_configs['bucket'];
        $this->store_directory = $aws_configs['store_directory_name']
            .self::S3_FOLDER_DELIMITER;
    }

    /**
     * S3上に保存されたタイムラインを取得する.
     *
     * @param string $file_name
     *
     * @return array
     */
    public function fetchTimeline($file_name)
    {
        $response = $this->s3_client->getObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $file_name,
            ]
        );

        return json_decode($response['Body'], true);
    }

    /**
     * S3に保存されているファイルの名前一覧を取得する.
     *
     * @return array
     */
    public function listFileName()
    {
        // Prefix でファイルが保存されているフォルダを指定する
        $file_array = $this->s3_client->listObjects(
            [
                'Bucket' => $this->bucket,
                'Prefix' => $this->store_directory,
            ]
        );

        $file_name_array = [];

        if (is_null($file_array['Contents'])) {
            return $file_name_array;
        }

        foreach ($file_array['Contents'] as $file_name) {
            $file_name_array[] = $file_name['Key'];
        }

        return $file_name_array;
    }

    /**
     * jsonデータをS3上に保存する.
     *
     * @param string $save_file_name
     * @param string $upload_data_json JSON
     *
     * @return Guzzle\Service\Resource\Model
     */
    public function storeJson($save_file_name, $upload_data_json)
    {
        return $this->s3_client->putObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $save_file_name,
                'Body' => $upload_data_json,
            ]
        );
    }

    /**
     * 最新のタイムラインをS3上に保存する.
     */
    public function storeLatestTimeline()
    {
        // twitter上の最新ツイート10件
        $latest_10_tweets = (new TwitterMediator())->fetch10Tweets();

        // S3上にファイルが１つも存在しなければ最新ツイート10件からファイルを作成する
        if (count($this->listFileName()) == 0) {
            $this->storeJson(
                $this->makeFileName(1),
                $latest_10_tweets
            );

            return;
        }

        $latest_file_name = $this->fetchLatestFileName();
        $latest_stored_timeline_array = $this->fetchTimeline($latest_file_name);

        // s3の最新ツイートのid
        $latest_stored_tweet_id = max(array_keys($latest_stored_timeline_array));

        $later_tweets_array = $this->pickLaterTweets($latest_10_tweets, $latest_stored_tweet_id);

        $merged_array = $latest_stored_timeline_array + $later_tweets_array;
        $chunk_10_tweets_array = array_chunk($merged_array, 10, true);

        $this->storeJson($latest_file_name, json_encode($chunk_10_tweets_array[0]));

        // 既存の最新ファイルのツイートに更新分をマージして10を超えていたら別ファイルに保存する
        if (!is_null($chunk_10_tweets_array[1])) {
            $latest_file_index = $this->fileNameToIndex($latest_file_name);
            $this->storeJson(
                $this->makeFileName($latest_file_index + 1),
                json_encode($chunk_10_tweets_array[1])
            );
        }
    }

    /**
     * S3上の最新ファイル名を取得する.
     *
     * @return string
     */
    private function fetchLatestFileName()
    {
        $file_name_array = $this->listFileName();

        // 連番のみの配列にする（数値形式の文字列が整数として比較されるのを利用するため）
        $file_index_array = $this->fileNameToIndex($file_name_array);

        $latest_file_index = max(array_values($file_index_array));

        return $this->makeFileName($latest_file_index);
    }

    /**
     * 配列内のS3用ファイル名から数値の部分のみ抜き出す.
     *
     * @param array|string $file_name
     *
     * @return array|string
     */
    private function fileNameToIndex($file_name)
    {
        $search = array($this->store_directory, self::FILE_EXTENTION);
        $replace = array('', '');

        // 連番のみの配列にする（数値形式の文字列が整数として比較されるのを利用するため）
        return str_replace($search, $replace, $file_name);
    }

    /**
     * S3保存用のファイル名を生成する.
     *
     * @param string|int $index
     *
     * @return string
     */
    private function makeFileName($index)
    {
        return $this->store_directory.$index.self::FILE_EXTENTION;
    }

    /**
     * 最新10件のツイートからS3に保存されていないものを選別する.
     *
     * @param string $latest_10_tweets       JSON
     * @param string $latest_stored_tweet_id
     *
     * @return array
     */
    private function pickLaterTweets($latest_10_tweets, $latest_stored_tweet_id)
    {
        $latest_10_tweets_array = json_decode($latest_10_tweets, true);
        // s3上に存在しないツイートのみを選別する
        $later_tweets_array = [];
        foreach ($latest_10_tweets_array as $key => $value) {
            if ($key > $latest_stored_tweet_id) {
                $later_tweets_array[$key] = $value;
            }
        }

        return $later_tweets_array;
    }
}
