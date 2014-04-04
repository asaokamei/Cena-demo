Cena-demo
=========

A demo for Cena project; a simple blog site.

Uses Doctrine2 as a base ORM, and NO FRAMEWORK, i.e. good old fashioned PHP site.

This site is provided as a sample demonstration
for Cena, and hence has insufficient security
measures. Not for public use.

#### Requirements

*   PHP 5.4 or higher
*   Apache
*   MySQL

Installation
------------

#### getting the Cena-demo

get the code from the github and use composer to install required packages.

```
git clone https://github.com/asaokamei/Cena-demo
cd Cena-demo
php composer.phar install
```

#### setting up

1.  configure the config/dbParam.php to reflect your db settings.
2.  run config/setup-db.php to create tables for the demo.
3.  run config/sample-db.php to create sample data.
4.  the demo is at ```public_legacy```.
    so, the simplest way to see the demo maybe, 
    ```
    ln -s /path/to/Cena-demo/public_legacy /doc/root/cena
    ```
5.  access ```http://localhost/cena/```.