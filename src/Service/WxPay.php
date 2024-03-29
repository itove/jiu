<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @author Al Zee <z@alz.ee>
 * @version
 * @todo
 */

namespace App\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Service\Wx;

class WxPay
{
    private $httpClient;
    private $wx;
    private $mchid;

    public function __construct(HttpClientInterface $client, Wx $wx)
    {
        $this->httpClient = $client;
        $this->wx = $wx;
        $this->mchid = $_ENV['WXPAY_MCH_ID'];
    }

    /**
     * @param array $batch  Batch info ['id', 'name', 'note', 'amount', 'scene']
     * @param array $list   List of transfer [['out_detail_no', 'transfer_amount', 'transfer_remark', 'openid', 'user_name', 'user_id_card'], ...]
     *
     * @return
     */

    public function toBalanceBatch(array $batch, array $list)
    {
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches';
        $data = [
            'appid' => $this->wx->getAppid(),
            'out_batch_no' => $batch['id'],
            'batch_name' => $batch['name'],
            'batch_remark' => $batch['note'],
            'total_amount' => $batch['amount'],
            'total_num' => count($list),
            'transfer_detail_list' => $list,
        ];
        if (isset($batch['scene'])) {
            $data['transfer_scene_id'] = $batch['scene'];
        }

        $json = json_encode($data);

        $sig = $this->genSig($url, 'POST', $json);
        $headers[] = "Authorization: {$sig}";
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept:application/json';

        /**
         * @resp { "out_batch_no" : "plfk2020042013", "batch_id" : "1030000071100999991182020050700019480001", "create_time" : "2015-05-20T13:29:35.120+08:00" }
         */
        return $this->httpClient->request('POST', $url, ['headers' => $headers, 'body' => $json])->toArray();
    }
    
    public function checkBatch(string $batch_id, $need_query_detail = 'true', $detail_status = 'ALL')
    {
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches/batch-id/' 
            . $batch_id . '?need_query_detail=' . $need_query_detail
            . '&detail_status=' . $detail_status;
        $sig = $this->genSig($url, 'GET', '');
        $headers[] = "Authorization: {$sig}";
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept:application/json';
        return $this->httpClient->request('GET', $url, ['headers' => $headers])->toArray();
    }
    
    public function checkDetail(string $batch_id, string $detail_id)
    {
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches/batch-id/' . $batch_id . '/details/detail-id/'
            . $detail_id;
        $sig = $this->genSig($url, 'GET', '');
        $headers[] = "Authorization: {$sig}";
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept:application/json';
        return $this->httpClient->request('GET', $url, ['headers' => $headers])->toArray();
    }

    public function toBank()
    {
    }

    public function genSig($url = "https://api.mch.weixin.qq.com/v3/certificates", $http_method = "GET", $body = "")
    {
        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?{$url_parts['query']}" : ""));
        $timestamp = time();
        $nonce = md5(uniqid());
        $merchant_id = $this->mchid;
        $serial_no = $this->getMchCertSN();
        $mch_private_key = $this->getPrivateKey();
        $message = $http_method."\n".
            $canonical_url."\n".
            $timestamp."\n".
            $nonce."\n".
            $body."\n";

        openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);

        $schema = 'WECHATPAY2-SHA256-RSA2048';
        $token = $schema . ' ' . sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $merchant_id, $nonce, $timestamp, $serial_no, $sign);

        return $token;
    }

    public static function getPrivateKey() {
        return openssl_get_privatekey(file_get_contents($_ENV['WXPAY_PRIVATE_KEY_PATH']));
    }

    public function getWXCertList()
    {
        $url = "https://api.mch.weixin.qq.com/v3/certificates";
        $method = 'GET';
        $merchant_id =$this->mchid;
        $serial_no = $this->getMchCertSN();
        $sig = $this->genSig($url, $method, "");
        // $header[] = 'User-Agent:https://zh.wikipedia.org/wiki/User_agent';
        $header[] = 'Content-Type: application/json';
        $header[] = 'Accept:application/json';
        $header[] = $sig;
        $resp = $this->httpclient->request($method, $url ,['headers' => $header]);
        $content = $resp->getContent(false);
        // return $this->json($resp);
    }

    public function getMchCertSN()
    {
        return openssl_x509_parse(file_get_contents($_ENV['WXPAY_CERT_PATH']))['serialNumberHex'];
    }
}
