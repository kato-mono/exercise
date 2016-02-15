<?php

namespace Monosense\Exercise;

class FileUtil
{
    /**
     * json形式で書かれたconfigを配列に変換して返す.
     *
     * @return array
     */
    public static function loadJsonConfig($file_path)
    {
        $json = file_get_contents($file_path);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $array = json_decode($json, true);

        return $array;
    }
}
