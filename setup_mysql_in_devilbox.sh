#!/usr/bin/env bash

mysql -uroot -hmysql -e "CREATE USER 'user'@'%' IDENTIFIED BY 'secret';"
mysql -uroot -hmysql -e "GRANT ALL PRIVILEGES ON * . * TO 'user'@'%';"