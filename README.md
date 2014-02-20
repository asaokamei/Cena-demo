Cena-demo
=========

A demo for Cena project.

Installation
------------

#### getting the Cena-demo

get the code from github and use composer.
```
git clone https://github.com/asaokamei/Cena-demo
cd Cena-demo
composer.phar install
```

#### setting up

1.  configure the config/dbParam.php to reflect your db settings.
2.  run config/setup-db.php to create tables for the demo.
3.  the demo directory is ```public_legacy```. 
    so, the simplest way to see the demo maybe, 
    ```
    ln -s /path/to/Cena-demo/public_legacy /doc/root/cena
    ```
