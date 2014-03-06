<?php
namespace Models;

use App\Model\AbstractModel;

class Sounder extends AbstractModel
{
    /**
     * @param string $host
     * @param int $port
     * @param int $waitTimeoutInSeconds
     * @return bool|int
     */
    public function ping($host, $port = 80, $waitTimeoutInSeconds = 1)
    {
        $timeAt = microtime(true);
        $result = false;
        if($fp = @fsockopen($host, $port, $errCode, $errStr, $waitTimeoutInSeconds)) {
            $result = microtime(true) - $timeAt;
            $result = intval($result * 1000);
            fclose($fp);
        }
        return $result;
    }

    /**
     * @param string $url
     * @param int $waitTimeoutInSeconds
     * @return string|bool
     */
    public function load($url, $waitTimeoutInSeconds = 10)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $waitTimeoutInSeconds
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    /**
     * @param string $url
     * @param int $waitTimeoutInSeconds
     * @return bool|int
     */
    public function getLoadTime($url, $waitTimeoutInSeconds = 10)
    {
        $timeAt = microtime(true);
        $result = false;
        if(self::load($url, $waitTimeoutInSeconds)) {
            $result = microtime(true) - $timeAt;
            $result = intval($result * 1000);
        };
        return $result;
    }

    /**
     * @param string $text
     * @param string $url
     * @param int $waitTimeoutInSeconds
     * @return bool
     */
    public function isTextOnPage($text, $url, $waitTimeoutInSeconds = 10)
    {
        $page = self::load($url, $waitTimeoutInSeconds);
        return (bool) strpos($page, $text);
    }
}