# Neo4jUserBundle

This bundle connects Symfony 3+ with Neo4j 3+ to store and retrieve user information from the neo4j-graph. 
This Bundle requires the Neo4j-PHP-OGM plugin from GraphAware: https://github.com/graphaware/neo4j-php-ogm
I have also used the MaterialDesignBootstrap framework in my TWIG files: http://mdbootstrap.com/

### Install

1. To install, simply upload to your symfony installation and activate the bundle. 
2. Create a 'Role' node with the property 'roleType' = 'ROLE_USER' in the Neo4j Graph
``` CREATE (r:Role{roleType:'ROLE_USER'}) ```
3. Change the connection information in the graph manager /Manager/GraphManager.php
4. Change the twig template files to suite your installation! These are found under /Resources/views

### Disclaimer
I am fairly new to Symfony and I expect the code to be mediocre at best. Improvements are welcome :)

