# MyCMS #

This is a tiny CMS web application, originally developed for university labs and research groups but can be used as a light and generic content management system anywhere.

[TOC]

## Requirements ##

- [Apache 2.2 or newer](http://www.apache.org/)
    - Rewrite module
- [MySQL 5.5 or newer](http://dev.mysql.com/downloads/)
- [PHP 5.3 or newer](https://php.net/downloads.php)
    - php_curl extension
- [Pear](http://pear.php.net/)
    - DB and DB_DataObject modules

## Installation ##

### Setting up the database ###

First off, note that you need to already have your database created, if you've not yet created it log into mysql and create a database there like this:

    mysql -u username -ppassword -h localhost

When you are logged into mysql server run the followings:

```sql
create database database_name;
show databases;
```

The last command shows the list of databases check if your created database is in the shown list.

Then import the _database.sql_ into your database from shell (not the mysql prompt), it is not a bash script file, to do that you should run something like the following:

    mysql -u username -ppassword -h localhost 'database_name' < database.sql

then check out your database, you should find many tables created in it. To do that from the mysql prompt (log into mysql again if you've logged out) run the followings

```sql
use database_name;
show tables;
```

It should show a long list of created tables.

Now you have your database ready to create the schema from. But before running _create-schema.sh_ you need to set your created database configuration in the _config.ini_. Within this file find _DB_DataObject_ where database properties are being set. Change the first line after that to set database access string. It has the format of `mysql://username:password@localhost/database_name` the default value for this variable is `mysql://root:@localhost/mycms`.

Now run the _create-schema.sh_ file. It should create lots of files in the schema directory. 

Check out the website, you shouldn't see any database related errors there.

### Setting up the _.htaccess_ ###

Set the RewriteBase variable in _.htaccess_ file based at project's root directory to its relative location to your domain directory. Otherwise all generated urls in the website would be invalid. To do that, uncomment the Rewrite command in this file and set it as the following:

If for example your apache virtual host points to /home/websites/ but the website resides at for example /home/websites/stavness/lab/web/mycms/, then you should rewrite the url base as the following to point to where the website index is located for every receiving erquest.

    RewriteBase /stavness/lab/web/mycms/

Save the file and close it. Now check the website everything should work properly.

### Setting up the _config.ini_ ###

Copy _config.ini_ file at root directory of the project to _\_config.ini\__ and set desired values for various settings in it.

One thing to do is to set the admin NSID, to do that simply write the NSID in front of the admin variable. Its default value is _root_, change it to something like this:

    admin = fan780

## Themeing ##

You can modify .tpl files in _/templates_ directory to change related designs for each content display format or pages.

## Adding New Content Types ##

Change _classes/contents.php_ file to add a new content type. Based upon the references in the newly defined content types you should add table definitions to the _database.sql_ file and also manually add your new tables to your current database. Then make sure to recreate the schema using _create-schema.sh_.

## Support New Languages ##

You can add support for new languages for static strings by adding new language files to the _lang_/ directory.
