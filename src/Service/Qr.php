<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @author Al Zee <z@alz.ee>
 * @version
 * @todo
 */

namespace App\Service;

use App\Service\Sn;
use App\Entity\Box;
use Doctrine\ORM\EntityManagerInterface;

class Qr
{
    private $em;

    public function __construct(EntityManagerInterface $em, $qrdir)
    {
        $this->em = $em;
        chdir($qrdir);
    }
    
    public function composite(int $boxid)
    {
        $box = $this->em->find('App\Entity\Box', $boxid);
        $boxSn = $box->getSn();
        $boxEnc = explode('.', $box->getCipher())[0];
        $this->gen($boxSn, $boxEnc, 0);
        $margin_l = 100;
        $margin_t = 100;
        
        // https://stackoverflow.com/a/39504523/7714132
        // shell_exec("convert -size 2000x2000 xc:white bg.png");
        shell_exec("composite -geometry +{$margin_l}+{$margin_t} {$boxSn}.png bg.png {$boxSn}.png");
        
        $bottles = $box->getBottles();
        foreach ($bottles as $bottle) {
            $sn = $bottle->getSn();
            $enc = explode('.', $bottle->getCipher())[0];
            $this->gen($sn, $enc, 1);
            $width = 645;
            $height = 739;
            $ratio = 0.5;
            $offset_x = 700;
            $offset_y = 410;
            $col = 3;
            $reszied_w = $width * $ratio;
            
            $bid = $bottle->getBid();
            if ($bid % $col === 0) {
                $current_col = 3;
            } else {
                $current_col = $bid % $col;
            }
            $offset_x += $reszied_w * ($current_col - 1) + $margin_l;
            $offset_y = $offset_y * ceil($bid / $col - 1) + $margin_t;
            
            shell_exec("composite -geometry {$reszied_w}x+{$offset_x}+{$offset_y} {$sn}.png {$boxSn}.png {$boxSn}.png");
            shell_exec("rm {$sn}.png");
        }
    }
    
    public function gen($sn, $enc, $type) {
        $url = $_ENV["WXQR_URL"];
        if (empty($url)) {
            echo 'env WXQR_URL not found';
            echo PHP_EOL;
            exit;
        }
        $text = "{$url}?t={$type}&s={$sn}&e={$enc}";
        // -s 15 witth 645px;
        shell_exec("qrencode -t png -s 15 -m 5 \"{$text}\" -o {$sn}.png");
        shell_exec("convert {$sn}.png -pointsize 72 label:{$sn}  -gravity Center -append {$sn}.png");
    }
}
