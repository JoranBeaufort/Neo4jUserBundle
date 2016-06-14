# Neo4jUserBundle

This bundle connects Symfony 3+ with Neo4j 3+ to store and retrieve user information from the neo4j-graph. 
This Bundle requires the Neo4j-PHP-OGM plugin from GraphAware: https://github.com/graphaware/neo4j-php-ogm
I have also used the MaterialDesignBootstrap framework in my TWIG files: http://mdbootstrap.com/

### Install

To install include following in your composer.json:

```
...
    "repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/joranbeaufort/neo4juserbundle"
        }
    ]
...
"require": {
    "joranbeaufort/neo4juserbundle": "dev-master"
}
...
```

Next, add following config to your `/app/config/config.yml` depending on your connection to Neo4j:

```
neo4j_user:
    username: neo4j
    password: neo4j
    url: localhost
    port: 7474       # Carefull, for bolt change to: 7687
    protocol: bolt   # Default: http
```

Add following to your `/app/config/parameters.yml` and change as desired:

```
    neo4j_user.directory: '%kernel.root_dir%/../web/users'
    neo4j_user.mail.subject.registration: 'Registration'
    neo4j_user.mail.subject.emailedit: 'Email Updated'
    neo4j_user.mail.subject.passwordreset: 'Password Reset'
``` 

Create a 'Role' node with the property 'roleType' = 'ROLE_USER' in the Neo4j Graph

``` 
CREATE (r:Role{roleType:'ROLE_USER'}) 
```

In your web directory, create a folder named "defaults" and add a default user image with the name "user.png".
Change the twig template files to suite your installation!

* This bundle uses Swiftmail. The swiftmail `mailer_user` is used as the default send address.

### Routes
This bundle comes with a few predefined routes:
```
neo4j_register:
    path:     /register
    defaults: { _controller: Neo4jUserBundle:Registration:register }

neo4j_register_check_email:
    path:     /checkmail
    defaults: { _controller: Neo4jUserBundle:Registration:checkEmail }

neo4j_register_confirm_email:
    path:     /confirm/{token}
    defaults: { _controller: Neo4jUserBundle:Registration:confirmed }
    
neo4j_login:
    path:     /login
    defaults: { _controller: Neo4jUserBundle:Security:login }
    
neo4j_password_reset:
    path:     /passwordreset
    defaults: { _controller: Neo4jUserBundle:Security:passwordReset }

neo4j_login_check:
    path: /login_check
    # no controller is bound to this route
    # as it's handled by the Security system
    
neo4j_logout:    
    path: /logout
    # no controller is bound to this route
    # as it's handled by the Security system
    
neo4j_profile:
    path: /user/{slug}
    defaults: { _controller: Neo4jUserBundle:User:profile }
    
neo4j_profile_edit:
    path: /edit/{slug}
    defaults: { _controller: Neo4jUserBundle:User:profileEdit }

neo4j_profile_update_email:
    path:     /updateemail/{token}
    defaults: { _controller: Neo4jUserBundle:User:confirmUpdatedEmail }
```


### Disclaimer
I am fairly new to Symfony and I expect the code to be mediocre at best. Improvements are welcome :)

### ToDo
Translation file

