# GRAPHSQLI LAB

GraphSQLi Lab is a PHP/MySQL web application with GraphQL API that contains 5 laboratories corresponding to 5 main types
of SQL Injection, which is:

* Union based SQL Injection
* Time based SQL Injection
* Error based SQL Injection
* Boolean based SQL Injection
* Out of band SQL Injection

The main goal of GraphSQLi Lab is to create an environment that supports lecturers/students to teach/practice about
GraphQL security and SQL Injection vulnerabilities. Also helps developers better understand the processes of securing
GraphQL web applications

The aim of this lab is to **practice exploiting forms of SQL Injection in a GraphQL API**, with **various levels of
difficulty**, with a simple straightforward interface.

- - -

## WARNING!

GraphSQLi Lab contains multi types of SQLi vulnerability! One of them can lead to information security risks if deployed
on Internet facing servers.<br>
**So do not deploy it on your hosting provider's public html folder or any Internet facing servers**, as they will be
compromised. It is recommended using a virtual machine (such as [VirtualBox](https://www.virtualbox.org/)
or [VMware](https://www.vmware.com/)), which is set to NAT networking mode. Inside a guest machine, you can download and
install [Laragon](https://laragon.org/download/index.html) for the web server and database.

- - -

## Installation

**Make sure you have entered the config directory in creating the config.php file with your configurations based on the
existing config.php.example file.**

### Windows + Laragon

You should download an install [Laragon](https://laragon.org/download/index.html) if you do not already have a web
server setup. Laragon is a program that provides the WAMP environment (which stands for Windows, Apache, MySQL and PHP).
With Laragon, you can completely install the WAMP environment easily, quickly and conveniently as well as manage them.

First, you need to turn on apache in Laragon and access the apache httpd.conf file by right clicking on the Laragon
interface. Add new port to apache and enable mod_rewrite on apache by adding the following line at the end of the
httpd.conf file

```
Listen 1002
LoadModule rewrite_module modules/mod_rewrite.so
```

The number 1002 is port number. You can change to any other port you want, as long as the port doesn't have any services
deployed

Then you need to download source code of GraphSQLi Lab and paste into C:\laragon\www. Then go to C:
\laragon\etc\apache2\sites-enabled and create file GraphQLi.conf with content:

```
<VirtualHost *:1002> 
    DocumentRoot "C:/laragon/www/GraphSQLi/"
    ServerName GraphSQLi.test
    ServerAlias *.GraphSQLi.test
    <Directory "C:/laragon/www/GraphSQLi/">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Finally, restart Laragon and point your browser to: `http://localhost:1002/`

### Linux

If you are using a Debian based Linux distribution, you will need to install the following packages (or their
equivalent):

```
apt-get -y install apache2 mariadb-server php php-mysqli php-gd libapache2-mod-php
```

After install package, go to /etc/apache2. Open and add the following line at the end of the ports.conf to open new port
with apache:

```
Listen 1002
```

Download source code of GraphSQLi Lab, paste into /var/www and change permission with

```
sudo chown www-data:www-data /var/www/GraphSQLi
sudo chmod -R 775 /var/www/GraphSQLi
```

Then go to /etc/apache2/sites-enabled, create file GraphQLi.conf with content:

```
<VirtualHost *:1002> 
    DocumentRoot "/var/www/GraphSQLi"
    ServerName GraphSQLi.test
    ServerAlias *.GraphSQLi.test
    <Directory "/var/www/GraphSQLi">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Finally, enable mod_rewrite and restart apache service with:

```
sudo a2enmod rewrite
sudo service apache2 restart
```

Finally, point your browser to: `http://localhost:1002/`

### Database Setup

Config for database connect is defined at config.php in config directory. By default, variables for connect database are
as follows:

```php
$_Config['db_server'] = '127.0.0.1';
$_Config['db_database'] = 'graphsqli_lab_db';
$_Config['db_user'] = 'graphsqli_lab';
$_Config['db_password'] = 'p@ssw0rd';
$_Config['db_port'] = '3306';
```

You should create a user other than root to connect with MySQL. Example: create graphsqli_lab user

```mysql
mysql> create user 'graphsqli_lab'@'localhost' identified by 'p@ssw0rd';
Query OK, 0 rows affected (0.01 sec)
```

And then grant permission for your user on your databse. Example: grant all permission on graphsqli_lab_db database for
graphsqli_lab user

```mysql
mysql> grant all on graphsqli_lab_db.* to 'graphsqli_lab'@'localhost';
Query OK, 0 rows affected (0.01 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

After grant permisson for your new user to config.php and change config of database similar with your config. Finally,
go to
`http://localhost:1002/setup` then click on the Create / Reset Database button. This will create / reset the database
for you with some data in.

- - -

## Other Configuration

### About config for Out of band SQLi:

Out of band SQLi in MySQL only works if MySQL is deployed on Windows. This due to load_file() function in MySQL on
Windows implements Windows API CreateFile. This API allows not only access to the files on the machine itself (in this
case the server running MySQL), but also allows access to files on the network. Which means that when MySQL is running
on Windows, it is possible to create a query that sends data over the internet.
**So to be able to practice this kind of attack, you must have MySQL deploy on Windows machine**.

In MySQL there exists a global system variable known as ‘secure_file_priv’. This variable is used to limit the effect of
data import and export operations, such as those performed by the LOAD DATA and SELECT ... INTO OUTFILE statements and
the LOAD_FILE() function:

* If set to the name of a directory, the server limits import and export operations to work only with files in that
  directory. The directory must exist, the server will not create it.
* If the variable is empty it has no effect, thus insecure configuration.
* If set to NULL, the server disables import and export operations. This value is permitted as of MySQL 5.5.53.

**Which means you must set this value to empty to be able to practice this kind of attack.**

### Instruction to set empty value for secure_file_priv on Windows

* Go to C:\ProgramData\MySQL\MySQL Server 8.0 and open file my.ini
* There you find under the section

```
  [mysqld]
```

* And then change value of secure-file-priv like this

```
  secure-file-priv=""
```

* Press Windows+R and type services.msc
* In interface of Services, find MySQL80 service, right click and choose restart
* You can check result by connect to MySQL with user root and use:

```mysql
mysql> SHOW VARIABLES LIKE "secure_file_priv";
+------------------+-------+
| Variable_name    | Value |
+------------------+-------+
| secure_file_priv |       |
+------------------+-------+
```
* After that, you must grant file permission for your database user. Example:

```mysql
mysql> GRANT FILE ON *.* to 'graphsqli_lab'@'localhost';
Query OK, 0 rows affected (0.01 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```


