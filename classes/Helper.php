<?php namespace Bombozama\LinkCheck\Classes;

class Helper
{
    public static function scanForUrls($string)
    {
        $expression = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
        preg_match_all($expression, $string, $matches);
        return ($matches[0]);
    }

    public static function getFullClassNameFromFile($pathToFile)
    {
        $fp = fopen($pathToFile, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp))
                break;

            $buffer.= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false)
                continue;

            for (; $i < count($tokens); $i++) {
                if($tokens[$i][0] === T_NAMESPACE)
                    for($j = $i+1; $j < count($tokens); $j++)
                        if($tokens[$j][0] === T_STRING)
                            $namespace .= '\\' . $tokens[$j][1];
                        else if($tokens[$j] === '{' || $tokens[$j] === ';')
                            break;

                if($tokens[$i][0] === T_CLASS)
                    for($j = $i+1; $j < count($tokens); $j++)
                        if($tokens[$j] === '{')
                            $class = $tokens[$i+2][1];
            }
        }
        return $namespace . '\\' . $class;
    }

    public static function getResponseCode($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);

        # In case of timeouts, let's throw out a 408 error "Request timeout"
        if($headers['http_code'] == 0)
            $headers['http_code'] = '408';

        return $headers['http_code'];
    }

}