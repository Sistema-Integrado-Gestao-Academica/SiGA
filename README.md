[![Stories in Ready](https://badge.waffle.io/Sistema-Integrado-Gestao-Academica/SiGA.png?label=ready&title=Ready)]
(https://waffle.io/Sistema-Integrado-Gestao-Academica/SiGA)
[![Stories in Progress](https://badge.waffle.io/Sistema-Integrado-Gestao-Academica/SiGA.png?label=in progress&title=In Progress)]
(https://waffle.io/Sistema-Integrado-Gestao-Academica/SiGA)

[![Throughput Graph](https://graphs.waffle.io/sistema-integrado-gestao-academica/siga/throughput.svg)](https://waffle.io/sistema-integrado-gestao-academica/siga/metrics)

SiGA - Sistema Integrado de Gestão Acadêmica
=====

![logo_siga](http://i.imgur.com/RmW4Ip7.png)

### SiGA
SiGA stands for ***Integrated Academic Management System*** (Sistema Integrado de Gestão Acadêmica in portuguese), which is a system that controls academic and finantial matters of an Educational Institute.

### Environment

SiGA relies on **Apache 2** server, **PHP** 5.5.9 and **MySQL** (Ver 14.14 Distrib 5.5.47). In order to run SiGA, you need this basic environment setted on your computer. You can easily find some info on the internet to install these.

Furthermore, it relies on **Composer** to manage dependencies. In order to install it, just follow the official tutorial on [Composer official web site](https://getcomposer.org/download/). By this time, this is the process (please check it on the official web site before doing):

      php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  
      php -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  
      sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
      
      php -r "unlink('composer-setup.php');"

### Modular Extension

SiGA now also relies on CodeIgniter [HMVC Modular Extensions](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc) (by wiredesignz) to make it modular. In order to use SiGA, now you have to add this capability to SiGA, and you can do this by doing the following steps:

* **1. Download the HMVC extension (available [here](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc)).**

* **2. Copy the 'MX' folder to *'application/third_party'*.**

* **3. Copy the content of 'core' folder (on HMVC extension) into *'application/core'*.**

Further configurations are already inside SiGA, but in case of any problem check the process of instalation on [HMVC Modular Extensions repository](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc).

### Running SiGA

This process assumes that you are running SiGA on a Unix-like OS, like Ubuntu or Debian.

* **1. Clone the repository in a accessible folder**

        cd your_accessible_folder

        git clone https://github.com/Sistema-Integrado-Gestao-Academica/SiGA.git

* **2. Create a symbolic link on */var/www/html* to the folder you've cloned SiGA, so you can access it on apache server**

        cd /var/www/html

        sudo ln -s your_accessible_folder/SiGA

* **3. Give the proper permission to the SiGA folder**

        sudo chmod 755 your_accessible_folder/SiGA -R

* **4. Create the database on MySQL**

  `CREATE DATABASE siga;`

* **5. Create the configuration files**

  * 5.1 Config
   
     * Save the 'config.php.template' file (on *application/config*) as 'config.php'.

     * Set the index page as 'index.php', if it isn't already.

  * 5.2 Database
   
     * Save the 'database.php.template' file (on *application/config*) as 'database.php'. 
  
     * Put your database username and password as:

             `'username' => 'your_username'`

             `'password' => 'your_password'`

  * 5.3 Migration
   
     * Save the 'migration.php.template' file (on *application/config*) as 'migration.php'. 

* **6. Run the migrations**

     * On *application/config/migration.php*, set the migration version (`$config['migration_version']`) to the latest file number on *application/migrations*.

     * In order to run the migrations and set up the database, type on the URL and wait:
         
           `localhost/SiGA/index.php/migrate`

         * **OBS.:** If is the first time that you are setting up SiGA, on *application/config/config.php* set the *'sess_driver'* and *'sess_save_path'* as follow, before run the migrations:

             `$config['sess_driver'] = 'files';`

             `$config['sess_save_path'] = NULL;`

               * After running the migrations, change back the *'sess_driver'* and *'sess_save_path'*, on *application/config/config.php* as it was before, probably like that:

             `$config['sess_driver'] = 'database';`

             `$config['sess_save_path'] = 'session';`

* **7. Install composer dependencies**

        composer install


Now you can access the SiGA in your browser via `localhost/SiGA`.
