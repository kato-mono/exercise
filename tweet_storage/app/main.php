<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>tweet storage</title>
</head>
<body>
  <section>
    <h1>最新のツイート10件</h1>
    <?php
        require_once './Path.php';
        require_once './TwitterMediator.php';
        require_once './S3Mediator.php';

        $latest_10_tweets = (new Monosense\Exercise\TwitterMediator())->fetch10Tweets();
        $latest_10_tweets_array = array_reverse(json_decode($latest_10_tweets, true));

        foreach ($latest_10_tweets_array as $value) {
            echo '<li>'.$value['text'].'</li>';
        }

        $s3_mediator = (new Monosense\Exercise\S3Mediator());
        $s3_mediator->storeLatestTimeline();

    ?>
  </section>
  <section>
    <h1>ダウンロードファイル一覧</h1>
    <?php
        $file_name_array = $s3_mediator->listFileName();
        foreach ($file_name_array as $file_name) {
            echo '<a href="./download.php?file_name='.$file_name.'">'.$file_name.'</a><br>';
        }
    ?>
  </section>
</body>
</html>
