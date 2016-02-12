<?php

class File_Util
{
    /**
     * json形式で書かれたconfigを読み込む
     */
    public static function load_json_config($file_path)
    {
        $json = file_get_contents($file_path);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $array = json_decode($json, true);

        return $array;
    }
}
