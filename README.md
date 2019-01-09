**Wastity**

Bonjour, voici comment installer le projet Wastity, une application permettant de :

- Mettre en commun des offres alimentaires disponibles proposées par les commerçants à proximité 
- Localiser les points de collecte / distribution pouvant accepter le don 
- Identifier les utilisateurs disponibles aux alentours pour récupérer la marchandise et l’acheminer au point de distribution.


Cloner Wastity sur votre serveur :
`https://github.com/WildCodeSchool/orleans-0918-php-wastity
`

Installer Composer, avec la commande :

`composer install
`


Installer yarn, avec la commande :

`yarn install
`

Créer une base de données, avec la commande :

`php bin/console doctrine:database:create
`


Mettre à jour la base de données, avec la commande :

`php bin/console doctrine:schema:update --force
`


Exécuter Webpack, avec la commande :

`yarn encore
`




**A propos / About**

**Wastity**
 est un projet PHP/Symfony 4.

