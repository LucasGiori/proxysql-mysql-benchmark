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

printf "$YELLOW[$(date)] Waiting for MySQL service on replica 1"
# INIT REPL ONCE SLAVE IS UP
RC=1
while [ $RC -eq 1 ]
do
  sleep 1
  printf "."
  mysqladmin ping -P3307 -uroot -proot > /dev/null 2>&1
  RC=$?
done
printf "$LIME_YELLOW\n"

printf "$POWDER_BLUE[$(date)] Configuring replica 1...$LIME_YELLOW\n"
mysql -P3307 -uroot -proot -e"RESET MASTER; CHANGE MASTER TO MASTER_HOST='mysql1',MASTER_USER='repl',MASTER_PASSWORD='repl',MASTER_PORT=3306,MASTER_AUTO_POSITION = 1;" > /dev/null 2>&1 
mysql -P3307 -uroot -proot -e"START SLAVE; SET GLOBAL READ_ONLY=1;" > /dev/null 2>&1

echo '#!/bin/sh 
while true; do
  printf "\033c"
  eval "$@"
  sleep 1
done' > /usr/local/bin/watch

chmod +x /usr/local/bin/watch