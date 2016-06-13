<?php
namespace JoranBeaufort\Neo4jUserBundle\Security\Token;

use JoranBeaufort\Neo4jUserBundle\Manager\GraphManager;
use JoranBeaufort\Neo4jUserBundle\Entity\User;

class TokenGenerator
{
    protected $graphManager;

    public function __construct(GraphManager $graphManager)
    {
        $this->graphManager = $graphManager;
    }
    
    private function crypto_rand_secure($min, $max)
            {
                $range = $max - $min;
                if ($range < 1) return $min; // not so random...
                $log = ceil(log($range, 2));
                $bytes = (int) ($log / 8) + 1; // length in bytes
                $bits = (int) $log + 1; // length in bits
                $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
                do {
                    $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                    $rnd = $rnd & $filter; // discard irrelevant bits
                } while ($rnd >= $range);
                return $min + $rnd;
            }

    private function getToken($length)
            {
                $token = "";
                $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
                $codeAlphabet.= "0123456789";
                $max = strlen($codeAlphabet) - 1;
                for ($i=0; $i < $length; $i++) {
                    $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
                }
                return $token;
            }
            
    public function generateConfirmationToken($l)
    {

        while (true)
        {
            
            $token = $this->getToken($l);
            $item=$this->graphManager->getClient()->getRepository(User::class)->findOneBy('confirmationToken', $token);
    
            if (!$item)
            {
                return $token;
            }
        }

        throw new \Exception('RandomIdGenerator worked hard, but could not generate unique ID :(');
    }
    
    public function generateImgToken($l)
    {

        while (true)
        {
            
            $token = $this->getToken($l);
            $item=$this->graphManager->getClient()->getRepository(User::class)->findOneBy('profileImage', $token);
    
            if (!$item)
            {
                return $token;
            }
        }

        throw new \Exception('RandomIdGenerator worked hard, but could not generate unique ID :(');
    }    
    
    public function generateUserToken($l)
    {

        while (true)
        {
            
            $token = $this->getToken($l);
            $item=$this->graphManager->getClient()->getRepository(User::class)->findOneBy('uid', $token);
    
            if (!$item)
            {
                return $token;
            }
        }

        throw new \Exception('RandomIdGenerator worked hard, but could not generate unique ID :(');
    }
}