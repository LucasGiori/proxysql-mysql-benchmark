BLACK=$'\e[30m'
RED=$'\e[31m'
GREEN=$'\e[32m'
YELLOW=$'\e[33m'
LIME_YELLOW=$'\e[93m'
POWDER_BLUE=$'\e[96m'
BLUE=$'\e[34m'
MAGENTA=$'\e[35m'
CYAN=$'\e[36m'
WHITE=$'\e[37m'
BRIGHT=$'\e[1m'
NORMAL=$'\e[0m'
BLINK=$'\e[5m'
REVERSE=$'\e[7m'
UNDERLINE=$'\e[4m'

printf "$YELLOW[$(date)] Waiting for MySQL service on primary"
# INIT REPL ONCE SLAVE IS UP
RC=1
while [ $RC -eq 1 ]
do
  sleep 1
  printf "."
  mysqladmin ping -P3306 -uroot -proot  > /dev/null 2>&1
  RC=$?
done
printf "$LIME_YELLOW\n"

printf "$POWDER_BLUE[$(date)] Configuring primary RO=false...$LIME_YELLOW\n"
mysql -P3306 -uroot -proot -e"SET GLOBAL read_only = OFF;SET GLOBAL super_read_only = OFF;SET PERSIST read_only = OFF;SET PERSIST super_read_only = OFF;" > /dev/null 2>&1

printf "$POWDER_BLUE[$(date)] Create additional database(s) on primary...['sysbench']$LIME_YELLOW\n"
mysql -P3306 -uroot -proot -e"CREATE USER monitor@'%' identified WITH mysql_native_password by 'monitor';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"GRANT usage,replication client on *.* to monitor@'%';" > /dev/null 2>&1 
mysql -P3306 -uroot -proot -e"CREATE DATABASE sysbench" > /dev/null 2>&1 
mysql -P3306 -uroot -proot -e"CREATE USER sysbench@'%' identified WITH mysql_native_password by 'sysbench';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"GRANT all on sysbench.* to sysbench@'%';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"CREATE USER 'repl'@'%' IDENTIFIED WITH mysql_native_password BY 'repl';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'repl'@'%';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"CREATE DATABASE poc;" > /dev/null 2>&1 
mysql -P3306 -uroot -proot -e"USE poc; CREATE TABLE IF NOT EXISTS volume_test ( id INT AUTO_INCREMENT PRIMARY KEY,random_data VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);" > /dev/null 2>&1 
mysql -P3306 -uroot -proot -e"CREATE USER poc@'%' identified WITH mysql_native_password by 'poc';" > /dev/null 2>&1
mysql -P3306 -uroot -proot -e"GRANT all on poc.* to poc@'%';" > /dev/null 2>&1

echo '#!/bin/sh 
while true; do
  printf "\033c"
  eval "$@"
  sleep 1
done' > /usr/local/bin/watch

chmod +x /usr/local/bin/watch

printf "$POWDER_BLUE$BRIGHT[$(date)] MySQL Provisioning COMPLETE!$NORMAL\n"