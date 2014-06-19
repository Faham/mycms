MyCMS
=====

This is a tiny CMS web application, originally developed for university labs and research groups but can be used as a generic content management system


Installation Requirements
=========================

- PHP
- php_curl extension
- MySQL
- Pear (packages needed: DB, DB_DataObject)
- Apache, Rewrite Module


Initialization
==============

- set the RewriteBase in .htaccess file based on your project location relative to your root domain directory
- copy _config.ini_ file at root directory of the project to config.ini and set desired values for various settings in it.
- create you database in mysql compliant to your settings in config.ini
- run database.sql queries (or import) to setup database tables and views
- run create-schema.sh to create the db interface schema


Change Front End Designs
========================

- manipulate *.tpl files in /templates directory to change related designs for each content display format ro pages

Add New Contents
========================

- change classes/contents.php file to add a new content, based upon the references in the newly defined content you should add table definitions to database.sql file and also manually define your tables to your current database.

Support New Languages
========================

- you can support new languages for static strings by adding new language files to lang/ directory.
