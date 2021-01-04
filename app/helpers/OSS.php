<?php


namespace app\helpers;

use OSS\OssClient;
use OSS\Core\OssException;

class OSS
{
    public static function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    /**
     * @return array
     */
    public static function getToken($prefix = 'test', $callback_body = [])
    {
        $id = env('ALI_OSS_KEY');          // 请填写您的AccessKeyId。
        $key = env('ALI_OSS_SECRET');     // 请填写您的AccessKeySecret。
        // $host的格式为 bucketname.endpoint，请替换为您的真实信息。
        $host = env('ALI_OSS_BUCKET_END_POINTY');
        $call_back = env('ALI_OSS_CALLBACK_URL');
        return self::_getToken($id, $key, $host, $prefix, $call_back, $callback_body);
    }

    public static function getDownUrl($object)
    {
        $timeout = 600;
        $bucket = env('ALI_OSS_BUCKET');
        try {
            $accessKeyId = env('ALI_OSS_KEY');
            $accessKeySecret = env('ALI_OSS_SECRET');
            // Endpoint以杭州为例，其它Region请按实际情况填写。
            $endpoint = env('ALI_OSS_END_POINTY');
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            // 生成GetObject的签名URL。
            $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
        } catch (OssException $e) {
//            printf(__FUNCTION__ . ": FAILED\n");
//            printf($e->getMessage() . "\n");
            return '';
        }
        return $signedUrl;
//        print(__FUNCTION__ . ": signedUrl: " . $signedUrl . "\n");
    }


    public static function _getToken($accessid, $key, $host, $prefix, $callback_url, $callback_body = [])
    {
        // $callbackUrl为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息。
        $dir = date('Ym') . '/';          // 用户上传文件时指定的前缀。
        if ($prefix) {
            $dir = $prefix . '/' . $dir;
        }
        $callback_body_arr = [
            'bucket' => '${bucket}',
            'etag' => '${etag}',
            'filename' => '${object}',
            'mimeType' => '${mimeType}',
            'height' => '${imageInfo.height}',
            'width' => '${imageInfo.width}',
            'format' => '${imageInfo.format}',
            'size' => '${size}',
            'host' => $host
        ];
        // 添加额外参数
        if (!empty($callback_body)) {
            foreach ($callback_body as $k => $item) {
                $callback_body_arr[$k] = $item;
            }
        }
        $callback_body_str = '';
        foreach ($callback_body_arr as $k => $item) {
            $callback_body_str .= '&' . $k . '=' . $item;
        }
        $callback_string = json_encode([
            'callbackUrl' => $callback_url,
            'callbackBody' => trim($callback_body_str, '&'),
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ]);

        $base64_callback_body = base64_encode($callback_string);
        $now = time();
        $expire = 30;  //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问。
        $end = $now + $expire;
        $expiration = self::gmt_iso8601($end);


        //最大文件大小.用户可以自己设置
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => 1048576000);
        $conditions[] = $condition;

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);
        $conditions[] = $start;
        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $accessid;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        $response['dir'] = $dir;  // 这个参数是设置用户上传文件时指定的前缀。
        return $response;
    }
}
