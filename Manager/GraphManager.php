<?php

namespace Neo4jUserBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GraphAware\Neo4j\OGM\EntityManager;


class GraphManager
{
    public function getClient()
    {
        $client = EntityManager::create('http://neo4j:password@localhost:7474');
        
        return $client;
    }
}
