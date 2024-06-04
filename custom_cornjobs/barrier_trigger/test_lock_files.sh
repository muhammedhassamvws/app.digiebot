 #!/bin/sh
 #
 #

COMMAND_1="/usr/bin/php -f /var/www/html/index.php admin barrier_trigger_live buy_order_cron_job"


x=0
SNOOZE=5
while [ $x -le 55 ]
do
  
 ME=`basename $0`;
LOCK="/var/lock/coin_meta_zenbtc.sh.LCK";
exec 8>$LOCK;

if flock -n -e 8; then :
  echo "Welcome $x times"
  ${COMMAND_1}
else
echo "Can't get file lock, Previos method is already running";
exit 1;
fi

  sleep ${SNOOZE}
  x=$(( $x + 5 ))
done

