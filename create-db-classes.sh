#!/bin/bash

BASEDIR=$(dirname $0)

pushd .
cd $BASEDIR
php $PHP_PEAR_INSTALL_DIR/DB/DataObject/createTables.php config.ini
popd