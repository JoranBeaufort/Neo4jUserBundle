<?php

namespace Neo4jUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Neo4jUserBundle\Manager\GraphManager; 
use Neo4jUserBundle\Security\Token\TokenGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Routing\RequestContext;


class DefaultController extends Controller
{

    public function indexAction()
    {   
        $userId='RTO0ckBf';
        $mapId='afoKKBJ1';
        $dataId='DlIr2W8vp';
        
        
        $file="/var/www/gisblocks.com/gisblocks/web/user/".$userId."/map/".$mapId."/".$dataId.".csv";
        $csv= file_get_contents($file);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $json = json_encode($array);
        print_r($array);
        die;
    
        
    
        // print($bake);
        // die;
        // 
        // echo $this->get('kernel')->getRootDir() . '/../web';
        // 
        // 
        // $fs = new Filesystem();
        // 
        // try {
        //     $fs->mkdir('/var/www/gisblocks.com/gisblocks/web/user/'.mt_rand());
        // } catch (IOExceptionInterface $e) {
        //     echo "An error occurred while creating your directory at ".$e->getPath();
        // }
        // die;
        // //$confirmationToken=$this->get('neo4j.token_generator')->generateConfirmationToken(24);
        // //echo $confirmationToken;
        // //die;
        // $q = "MATCH (n:User)-[r:HAS_ROLE]->(m:Role) WHERE n.username='mfbaer' OR n.email = '' RETURN n, m.name";
        // $result = $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($q);
        // $userData = $result->getRows()['m.name'];
        // 
        // $r = "MATCH (n:User{usernameCanonical:'mfbaer'})-[r:HAS_ROLE]->(m) RETURN m.name";
        // $userRoles =  $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($r)->getRows();
        // $roles = $userRoles['m.name'];
        // var_dump($userData);
        // echo '<br>';
        // var_dump($roles);
        // die;
        // 
        // $e = "MATCH (n:User{usernameCanonical:'mbaer'}) RETURN n.enabled";
        // $enabled =  $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($e)->getRows();
        // $isEnabled = $enabled['n.enabled'];
        // echo $isEnabled[0];
        // die;
        // 
        // //$q = 'MATCH (n:User{usernameCanonical:"mfbaer"})-[r:HAS_ROLE]->(m) RETURN n,m';
        // $r = 'MATCH (n:User{usernameCanonical:"mfbaer"})-[r:HAS_ROLE]->(m) RETURN m.name';
        // 
        // // $response = $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($q)->getResult()->getSingleNode();
        // // $response = $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($r)->getResult();
        // $response = $this->get('neo4j.graph_manager')->getClient()->sendCypherQuery($r)->getRows();
        // 
        // if(!$response){
        //     $print = "user not found";
        // }else{
        //      var_dump($response['m.name']);
        //     // foreach($response['m.name'] as $role) echo $role;
        // 
        //        
        // 
        // }
        // 
        // die();
        
        return $this->render('Neo4jUserBundle:Default:index.html.twig', array('userid' => $userId, 'mapid' => $mapId));
    }
}
