<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Org;
use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\Orders;
use App\Entity\OrderItems;
use App\Entity\Returns;
use App\Entity\ReturnItems;
use App\Entity\Retail;
use App\Entity\OrderRestaurant;
use App\Entity\Scan;
use App\Entity\User;
use App\Entity\Box;
use App\Entity\Bottle;
use App\Entity\Choice;
use App\Entity\Conf;
use App\Entity\Claim;
use App\Entity\Settle;
use App\Entity\Prize;
use App\Entity\Collect;
use App\Entity\Industry;
use App\Entity\Transaction;
use App\Entity\RetailReturn;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\Sms;
use App\Service\Sn;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\WxPay;

#[Route('/api')]
class ApiController extends AbstractController
{
    private $doctrine;
    private $translator;
    private $wxpay;
    private $sms;

    public function __construct(ManagerRegistry $doctrine, TranslatorInterface $translator, WxPay $wxpay, Sms $sms)
    {
        $this->doctrine = $doctrine;
        $this->translator = $translator;
        $this->wxpay = $wxpay;
        $this->sms = $sms;
    }

    #[Route('/return/new', methods: ['POST'])]
    public function returnNew(Request $request): JsonResponse
    {
        $params  = $request->toArray();
        $sender = $this->doctrine->getRepository(Org::class)->find($params['senderid']);
        $recipient = $this->doctrine->getRepository(Org::class)->find($params['recipientid']);
        $product = $this->doctrine->getRepository(Product::class)->find($params['product']);
        $quantity = $params['quantity'];
        $em = $this->doctrine->getManager();

        $item = new ReturnItems();
        $item->setProduct($product);
        $item->setQuantity($quantity);
        $em->persist($item);
        $em->flush();

        $ret = new Returns();
        $ret->setSender($sender);
        $ret->setRecipient($recipient);
        $ret->setNote($params['note']);
        $ret->addReturnItem($item);

        $em->persist($ret);
        $em->flush();

        $item->setRet($ret);

        $em->flush();

        return $this->json([
            'code' => 0,
        ]);
    }

    #[Route('/dine/new', methods: ['POST'])]
    public function dineNew(Request $request): JsonResponse
    {
        $params  = $request->toArray();
        $restaurant = $this->doctrine->getRepository(Org::class)->find($params['oid']);
        $customer = $this->doctrine->getRepository(User::class)->find($params['uid']);
        $rand = $params['timestamp'];
        $voucher = $params['voucher'];
        $em = $this->doctrine->getManager();

        $dine = new OrderRestaurant();
        $dine->setRestaurant($restaurant);
        $dine->setCustomer($customer);
        $dine->setVoucher($voucher);
        $em->persist($dine);

        $scan = new Scan();
        $scan->setCustomer($customer);
        $scan->setOrg($restaurant);
        $scan->setRand($rand);
        $em->persist($scan);

        $em->flush();

        return $this->json([
            'code' => 0,
        ]);
    }

    #[Route('/refretail/{cid}', requirements: ['cid' => '\d+'],  methods: ['GET'])]
    public function refRetail(int $cid): JsonResponse
    {
        $myRefs = $this->doctrine->getRepository(User::class)->findBy(['referrer' => $cid]);
        // dump($myRefs);
        // $refRetails = $this->doctrine->getRepository(Retail::class)->findByMyRefs($myRefs);
        $refRetails = [];
        foreach ($myRefs as $v) {
            // dump($v);
            $retails = $this->doctrine->getRepository(Retail::class)->findBy(['customer' => $v]);
            // dump($retails);
            $refRetails = array_merge($refRetails, $retails);
        }
        // dump($refRetails);
        return $this->json($refRetails);
    }

    #[Route('/orgs-have-stock-of-product/{pid}', methods: ['GET'])]
    public function orgsHaveStock(int $pid): JsonResponse
    {
        $product = $this->doctrine->getRepository(Product::class)->find($pid);
        $stocks = $this->doctrine->getRepository(Stock::class)->findBy(['product' => $product]);
        $orgs = [];
        foreach ($stocks as $stock) {
            $orgType = $stock->getOrg()->getType();
            if ($orgType != 0 && $orgType != 1 && $orgType != 5) {
                array_push($orgs, $stock->getOrg());
            }
        }
        return $this->json($orgs);
    }

    #[Route('/org/new', methods: ['POST'])]
    public function createOrgAndBindAdmin(Request $request): JsonResponse
    {
        $params  = $request->toArray();
        $em = $this->doctrine->getManager();
        $admin = $this->doctrine->getRepository(User::class)->find($params['uid']);
        
        $org = new Org();
        $org->setAddress($params['address']);
        $org->setContact($params['contact']);
        $org->setArea($params['area']);
        $org->setName($params['name']);
        $org->setPhone($params['phone']);
        $org->setType($params['type']);
        $up = $this->doctrine->getRepository(Org::class)->find($params['upstreamId']);
        $org->setUpstream($up);
        $industry = $this->doctrine->getRepository(Industry::class)->find($params['industryId']);
        $org->setIndustry($industry);
        $org->setAdmin($admin);
        $em->persist($org);
        $admin->setOrg($org);
        $em->flush();
        $code = 0;

        return $this->json(['code' => $code]);
    }

    #[Route('/choices/{taxon}')]
    public function getChoices($taxon): JsonResponse
    {
        $choice = array_flip(Choice::get($taxon));
        $arr = [];
        foreach($choice as $v => $k){
            $arr[] = [
                'id' => $v,
                'value' => $this->translator->trans($k)
            ];
        }
        return $this->json($arr);
    }

    #[Route('/scan/ret', methods: ['POST'])]
    public function scanReturn(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params  = $request->toArray();
        $oid = $params['oid'];
        $sn = $params['s'];
        $boxid = Sn::toId($sn);
        $cipher = $params['e'];
        $org = $em->getRepository(Org::class)->find($oid);
        $box = $em->getRepository(Box::class)->find($boxid);
        // Verify cipher
        $cipher0 = explode('.', $box->getCipher())[0];
        if ($cipher !== $cipher0) {
            $code = 11;
            $msg = '错误的二维码';
            // $msg = 'Wrong cipher.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // Check upstream
        if ($org !== $box->getOrg()) {
            $code = 12;
            $msg = '您不能退货此商品';
            // $msg = 'You can not return this box.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // Check if have bottle sold
        if ($box->getBottleSold() > 0) {
            $code = 13;
            $msg = '已有单瓶售出';
            // $msg = 'You can not return this box.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // If all pass, create new ret
        $product = $box->getProduct();
        $qty = 1;
        
        $item = new ReturnItems();
        $item->setProduct($product);
        $item->setQuantity($qty);
        $item->addBox($box);
        $em->persist($item);

        $ret = new Returns();
        $ret->setSender($org);
        $ret->setRecipient($org->getUpstream());
        $ret->addReturnItem($item);
        $em->persist($ret);
        
        $item->setRet($ret);

        $em->flush();
        $ret->setStatus(5);
        $em->flush();
       
        $code = 0;
        $msg = '已退货';
        // $msg = 'Done';
        $ret = ['product' => $product, 'qty' => $qty];
        
        return $this->json(['code' => $code, 'msg' => $msg, 'ord' => $ret]);
    }

    #[Route('/scan/box', methods: ['POST'])]
    public function scanBox(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params  = $request->toArray();
        $oid = $params['oid'];
        $sn = $params['s'];
        $boxid = Sn::toId($sn);
        $cipher = $params['e'];
        $org = $em->getRepository(Org::class)->find($oid);
        $box = $em->getRepository(Box::class)->find($boxid);
        // Verify cipher
        $cipher0 = explode('.', $box->getCipher())[0];
        if ($cipher !== $cipher0) {
            $code = 11;
            $msg = '错误的二维码';
            // $msg = 'Wrong cipher.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // Check upstream
        if ($org->getUpstream() !== $box->getOrg()) {
            $code = 12;
            $msg = '您不能进货此商品';
            // $msg = 'You can not order this box.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // Check forRestaurant
        if ($org->getType() !== 3 && $box->getPack()->isForRestaurant()) {
            $code = 13;
            $msg = '此商品限定餐厅';
            // $msg = 'Only for restaurants';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        // If all pass, create new order
        $product = $box->getProduct();
        $qty = 1;
        
        $item = new OrderItems();
        $item->setProduct($product);
        $item->setQuantity($qty);
        $item->addBox($box);
        $em->persist($item);
        
        $order = new Orders();
        $order->setSeller($org->getUpstream());
        $order->setBuyer($org);
        $order->addOrderItem($item);
        $em->persist($order);
        
        $item->setOrd($order);

        $em->flush();
        $order->setStatus(5);
        $em->flush();
        
        $code = 0;
        $msg = '已入库';
        // $msg = 'Done';
        $ord = ['product' => $product, 'qty' => $qty];
        
        return $this->json(['code' => $code, 'msg' => $msg, 'ord' => $ord]);
    }
    
    #[Route('/scan/bottle', methods: ['POST'])]
    public function scanBottle(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params  = $request->toArray();
        $uid = $params['uid'];
        $sn = $params['s'];
        $bid = Sn::toId($sn);
        $cipher = $params['e'];
        $user = $em->getRepository(User::class)->find($uid);
        $bottle = $em->getRepository(Bottle::class)->findOneBy(['sn' => $sn]);
        $box = $bottle->getBox();
        $product = $box->getProduct();
        $org = $box->getOrg();
        $qty = 1;
        // Verify cipher
        $cipher0 = explode('.', $bottle->getCipher())[0];
        if ($cipher !== $cipher0) {
            $code = 11;
            $msg = '错误的二维码';
            // $msg = 'Wrong cipher.';
            return $this->json(['code' => $code, 'msg' => $msg]);
        }
        
        // If unsold
        if ($bottle->getStatus() === 0) {
            // $retail = $em->getRepository(Retail::class)->findOneBy(['bottle' => $bottle]);
            // if (! is_null($retail)) {
            //     $code = 14;
            //     $msg = '此二维码已抽奖';
            //     // $msg = 'Can not draw again.';
            //     return $this->json(['code' => $code, 'msg' => $msg]);
            // }
        
            // Only sold if box is in stores
            if ($org->getType() === 2 || $org->getType() === 12 || $org->getType() === 3) {
                $retail = new Retail();
                $retail->setStore($org);
                $retail->setCustomer($user);
                $retail->setProduct($product);
                $retail->setQuantity($qty);
                $retail->setBottle($bottle);
                $em->persist($retail);
                $em->flush();
                
                $code = 0;
                // $msg = 'Done.';
                $msg = "获得奖品";
                $prize = $bottle->getPrize();
                if (is_null($retail->getClaim())) {
                    // collect amount 1, set 100 because frontend will divide 100
                    $value = 100;
                } else {
                    $value = $retail->getClaim()->getPrize()->getToCustomer();
                }
                return $this->json([
                    'code' => $code,
                    'msg' => $msg,
                    'prize' => $prize->getName(),
                    'value' => $value,
                ]);
            } else {
                $code = 12;
                // $msg = 'Bottle not in store.';
                $msg = '您不能购买此商品';
                return $this->json(['code' => $code, 'msg' => $msg]);
            }
        }
        // if sold
        if ($bottle->getStatus() === 1) {
            // If is waiter and box pack is for restaurant
            if (in_array('ROLE_WAITER', $user->getRoles()) && $box->getPack()->isForRestaurant()) {
                // If no waiter scanned yet
                if (is_null($bottle->getWaiter())) {
                    // Tip waiter
                    $tip = $product->getWaiterTip();
                    $user->setWithdrawable($user->getWithdrawable() + $tip);
                    $transaction = new Transaction();
                    $transaction->setUser($user);
                    $transaction->setType(4);
                    $transaction->setAmount($tip);
                    $em->persist($transaction);
                    $bottle->setWaiter($user);
                    // $bottle->setStatus(2);
                    $em->flush();
                    $code = 1;
                    $msg = "恭喜您获得提现金额";
                    // $msg = 'Waiter tipped.';
                    return $this->json(['code' => $code, 'msg' => $msg, 'tip' => $tip]);
                } else {
                    $code = 13;
                    $msg = '此二维码已使用';
                    // $msg = 'Can not tip again.';
                    return $this->json(['code' => $code, 'msg' => $msg]);
                }
            } else {
                $code = 14;
                $msg = '此二维码已抽奖';
                // $msg = 'Can not draw again.';
                return $this->json(['code' => $code, 'msg' => $msg]);
            }
        }
    }
    
    #[Route('/scan/storeman', methods: ['POST'])]
    public function scanStoreman(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params  = $request->toArray();
        $oid = $params['oid'];
        $sns = $params['sns'];
        $qty = count($sns);
        $pid = $params['pid'];
        $product = $em->getRepository(Product::class)->find($pid);
        $buyer = $em->getRepository(Org::class)->find($oid);
        $head = $em->getRepository(Org::class)->findOneBy(['type' => 0]);
        // Verify cipher
        
        $item = new OrderItems();
        $item->setProduct($product);
        $item->setQuantity($qty);
        foreach ($sns as $sn) {
            $box = $em->getRepository(Box::class)->find(Sn::toId($sn));
            // Check if box is in head
            if ($box->getOrg() === $head) {
                $item->addBox($box);
            }
        }
        $em->persist($item);
        
        $order = new Orders();
        $order->setSeller($head);
        $order->setBuyer($buyer);
        $order->addOrderItem($item);
        $em->persist($order);
        
        $item->setOrd($order);

        $em->flush();
        
        $code = 0;
        $msg = '已生成订单';
        // $msg = 'Done';
        $ord = ['product' => $product, 'qty' => $qty];
        
        return $this->json(['code' => $code, 'msg' => $msg, 'ord' => $ord]);
    }
    
    #[Route('/org/staff/add', methods: ['POST'])]
    public function addstaff(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $user = $em->getRepository(User::class)->find($params['uid']);
        // TODO: check if $user have perm
        $staff = $em->getRepository(User::class)->find($params['staffId']);
        $org = $em->getRepository(Org::class)->find($params['oid']);
        $staff->setOrg($org);
        if ($org->getType === 3) {
            $staff->addRole('ROLE_WAITER');
        }
        $em->flush();
        return $this->json(['code' => 0]);
    }
    
    #[Route('/org/admin/bind', methods: ['POST'])]
    public function bindOrgAdmin(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $user = $em->getRepository(User::class)->find($params['uid']);
        // TODO: check if $user have perm
        $admin = $em->getRepository(User::class)->find($params['adminId']);
        $org = $em->getRepository(Org::class)->find($params['oid']);
        $org->setAdmin($admin);
        $em->flush();
        return $this->json(['code' => 0]);
    }
    
    #[Route('/waiter/reg', methods: ['POST'])]
    public function watierReg(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $user = $em->getRepository(User::class)->find($params['uid']);
        // TODO: check if actual have salesman role
        $waiter = $em->getRepository(User::class)->find($params['waiterId']);
        if (! is_null($waiter)) {
            $waiter->addRole('waiter');
            $em->flush();
        }
        return $this->json(['code' => 0]);
    }
    
    #[Route('/claim/done', methods: ['POST'])]
    public function setClaimed(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $claim = $em->getRepository(Claim::class)->find($params['id']);
        
        if ($claim->getStatus() === 0) {
            $product = $claim->getProduct();
            $org = $em->getRepository(Org::class)->find($params['oid']);
            $tip = $product->getStoreTip();
            $claim->setServeStore($org);
            $claim->setStatus(1);
            /**
            $org->setWithdrawable($org->getWithdrawable() + $tip);
            $transaction = new Transaction();
            $transaction->setOrg($org);
            $transaction->setType(14);
            $transaction->setAmount($tip);
            $em->persist($transaction);
             */
            $em->flush();
            $code = 0;
        } else {
            $code = 1;
        }
        
        return $this->json(['code' => $code, 'tip' => $tip]);
    }
    
    #[Route('/claim/settle', methods: ['POST'])]
    public function setttleClaim(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $type = $params['type'];
        $claim = $em->getRepository(Claim::class)->find($params['id']);
        $salesman = $em->getRepository(User::class)->find($params['uid']);
        $product = $claim->getProduct();
        $pass = false;
        
        $code = 0;
        $msg = 'done';
        // TODO: check if actual have salesman role
        // if ($salesman) {
        // }
        if ($type === 'user') {
            return $this->json(['code' => 1, 'msg' => 'Salesman can not settle for customer']);
        }
        if ($type === 'store' && $claim->isStoreSettled() === false ) {
            $claim->setStoreSettled(true);
            $pass = true;
            $phone = $claim->getStore()->getPhone();
        }
        if ($type === 'serveStore' && $claim->isServeStoreSettled() === false) {
            $claim->setServeStoreSettled(true);
            $pass = true;
            $phone = $claim->getServeStore()->getPhone();
        }
        if ($pass) {
            /**
            $tip = $product->getSalesmanTip();
            $salesman->setWithdrawable($salesman->getWithdrawable() + $tip);
            $transaction = new Transaction();
            $transaction->setUser($salesman);
            $transaction->setType(5);
            $transaction->setAmount($tip);
            $em->persist($transaction);
            */
            
            $settle = new Settle();
            $settle->setSalesman($salesman);
            $settle->setClaim($claim);
            $settle->setProduct($product);
            $settle->setType(Choice::SETTLE_TYPES[$type]);
            $em->persist($settle);
            $em->flush();
            
            if (!is_null($phone)) {
                $this->sms->send($phone, 'settle_notify', ['prize' => $claim->getPrize()->getName()]);
            }
        } else {
            $code = 2;
            $msg = 'cant settle again';
        }
        return $this->json(['code' => $code, 'msg' => $msg, 'tip' => $tip]);
    }
    
    #[Route('/withdrawable_move_to_person', methods: ['POST'])]
    public function withdrawable_move_to_person(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $user = $em->getRepository(User::class)->find($params['uid']);
        $org = $em->getRepository(Org::class)->find($params['oid']);
        $admin = $org->getAdmin();
        $orgW = $org->getWithdrawable();
        //TODO: check if $user have perm
        if (! is_null($admin)) {
            $admin->setWithdrawable($admin->getWithdrawable() + $orgW);
            $transaction = new Transaction();
            $transaction->setUser($admin);
            $transaction->setType(6);
            $transaction->setAmount($orgW);
            $em->persist($transaction);
            
            $org->setWithdrawable(0);
            $transaction = new Transaction();
            $transaction->setOrg($org);
            $transaction->setType(21);
            $transaction->setAmount(-$orgW);
            $em->persist($transaction);
            
            $em->flush();
        }
        
        return $this->json(['code' => 0]);
    }
    
    #[Route('/collect', methods: ['POST'])]
    public function collect(Request $request): Response
    {
        $em = $this->doctrine->getManager();
        $params = $request->toArray();
        $user = $em->getRepository(User::class)->find($params['uid']);
        $collect = $em->getRepository(Collect::class)->find($params['id']);
        $product = $collect->getProduct();
        $qty = $collect->getQty();
        if ($qty < 3) {
            $code = 1;
        } else {
            $claim = new Claim();
            $claim->setStatus(0);
            $prize = $em->getRepository(Prize::class)->findOneBy(['label' => 'onemore']);
            $claim->setPrize($prize);
            $claim->setProduct($product);
            $collect->setQty($qty - 3);
            if (isset($params['type'])) {
                $claim->setStore($org);
            } else {
                $claim->setCustomer($user);
            }
            $em->persist($claim);
            $em->flush();
            $code = 0;
        }
        
        
        return $this->json(['code' => $code]);
    }
    
    #[Route('/checkbatch/{id}', methods: ['GET'])]
    public function checkBatch(string $id): Response
    {
        $resp = $this->wxpay->checkBatch($id);
        
        return new Response('<body></body>');
    }
    
    #[Route('/checkdetail/{batchid}/{detailid}', methods: ['GET'])]
    public function checkDetail(string $batchid, string $detailid): Response
    {
        $resp = $this->wxpay->checkDetail($batchid, $detailid);
        
        return new Response('<body></body>');
    }
    
    #[Route('/claim/salesman/{uid}', requirements: ['uid' => '\d+'],  methods: ['GET'])]
    public function claimOfSalesman(int $uid): JsonResponse
    {
        $myStoreClaims = $this->doctrine->getRepository(Claim::class)->findSalesmanStore($uid);
        $myServeStoreClaims = $this->doctrine->getRepository(Claim::class)->findSalesmanServeStore($uid);
        $claims = [];
        foreach ($myServeStoreClaims as $c) {
            $claim = [];
            $claim['title'] = $c->getProduct()->getName() . ' ' . $c->getServeStore()->getName() . ' (服务门店)';
            $claim['createdAt'] = $c->getCreatedAt();
            $claim['status'] = $c->isServeStoreSettled();
            $claims[] = $claim;
        }
        foreach ($myStoreClaims as $c) {
            $claim = [];
            $claim['title'] = $c->getProduct()->getName() . ' ' . $c->getStore()->getName() . ' (售出门店)';
            $claim['createdAt'] = $c->getCreatedAt();
            $claim['status'] = $c->isStoreSettled();
            $claims[] = $claim;
        }
        
        return $this->json($claims);
    }
}
