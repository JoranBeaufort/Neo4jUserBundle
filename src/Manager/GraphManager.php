<?php

namespace JoranBeaufort\Neo4jUserBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GraphAware\Neo4j\OGM\EntityManager;


class GraphManager
{
    private $user;
    private $pass;
    private $url;
    private $port;

    public function setConfig( $config )
    {
        $this->user = $config['username'];
        $this->pass = $config['password'];
        $this->url  = $config['url'];
        $this->port = $config['port'];
    }
    
    public function getClient()
    {
        $client = EntityManager::create('http://'.$this->user.':'.$this->pass.'@'.$this->url.':'.$this->port);
        
        return $client;
    }
}
