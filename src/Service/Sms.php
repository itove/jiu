<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @author Al Zee <z@alz.ee>
 * @version
 * @todo
 */

namespace App\Service;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Log\LoggerInterface;

class Sms
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $accessKeyId = $_ENV['SMS_ACCESS_KEY_ID'];
        $accessKeySecret = $_ENV['SMS_ACCESS_KEY_SECRET'];

        $config = new Config([
            "accessKeyId" => $accessKeyId,
            "accessKeySecret" => $accessKeySecret 
        ]);
        $this->client = new Dysmsapi($config);
        $this->logger = $logger;
    }

    public function getTemplateList($page = 1, $pageSize = 50)
    {
        $opts = new RuntimeOptions([]);
        $querySmsTemplateListRequest = new QuerySmsTemplateListRequest([]);
        $resp = $this->client->querySmsTemplateListWithOptions($querySmsTemplateListRequest, $opts);
        return $resp->body->smsTemplateList;
    }

    public function send($phone, $type = 'verify', $params = [])
    {
        $accessKeyId = $_ENV['SMS_ACCESS_KEY_ID'];
        $accessKeySecret = $_ENV['SMS_ACCESS_KEY_SECRET'];
        $signName = $_ENV['SMS_SIGNATURE'];
        switch($type){
            case 'verify':
                $templateCode = 'SMS_268695017';
                break;
            case 'login':
                $templateCode = 'SMS_211140348';
                break;
            case 'alert':
                $templateCode = 'SMS_211140347';
                break;
            case 'regsiter':
                $templateCode = 'SMS_211140346';
                break;
            case 'passwd':
                $templateCode = 'SMS_211140345';
                break;
            case 'usermod':
                $templateCode = 'SMS_211140344';
                break;
            case 'orgReg':
                $templateCode = 'SMS_268690826';
                break;
            default:
                $templateCode = 'SMS_211140348';
        }

        if ($type == 'verify') {
            $params = ['code' => mt_rand(100000, 999999)];
        }
        $templateParam = json_encode($params);

        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $phone,
            "signName" => $signName,
            "templateCode" => $templateCode,
            "templateParam" => $templateParam
        ]);
        $resp = $this->client->sendSms($sendSmsRequest);
        $this->logger->info("SMS send response: type: {$type}, phone: {$phone}, code: {$resp->body->code}, message: {$resp->body->message}, templateParam: {$templateParam}");
        return $resp;

        /*
        $cache = new RedisAdapter(RedisAdapter::createConnection('redis://localhost'));
        // $cache = new FilesystemAdapter();

        $cache->clear($phone);

        $cache->get($phone, function (ItemInterface $item) use ($code){
            $item->expiresAfter(300);
            return $code;
        });
         */
    }
}
