## Gesior

### Requirements:
* [Apache](http://www.apache.org/) with [mod_rewrite](http://httpd.apache.org/docs/current/mod/mod_rewrite.html) enabled + [PHP](http://php.net) Version 5.6 or newer

### How to install:
* Clone the Gesior from GitHub.
* change the permission for write in /cache.

```bash
sudo chmod -R 777 /cache
```

### Tips and Tricks:
* This website have some security implements, here you can use apache2 to apply them.
* run these commands to maximize your security.
````bash
sudo a2enmod headers
sudo a2enmod rewrite 
````
* on ubuntu 16.06 or 14.04 go to apache folder and edit your config.
* run:
````bash
sudo vim /etc/apache2/apache2.conf 
````
and search for something like this: 
```markdown
<Directory PATH_TO_YOUR_WEBSITE>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted         
</Directory>
```

* and add something like this /\

### PHP NEEDS THAT FOLLOWING
```bash
sudo apt-get install php5-gd
sudo apt-get install php5-curl
```

Make sure curl is enabled in the php.ini file. For me it's in /etc/php5/apache2/php.ini, if you can't find it, this line might be in /etc/php5/conf.d/curl.ini. Make sure the line :
extension=curl.so

now restart apache.:
```bash
sudo /etc/init.d/apache2 restart
```
or
```bash
sudo service apache2 restart
```
