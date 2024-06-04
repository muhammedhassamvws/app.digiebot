z #!/bin/sh
 #
 #

COMMAND_1="/usr/local/bin/php -f /home/digiebot/public_html/app.digiebot.com/index.php admin barrier_trigger_live buy_order_cron_job"


x=0
SNOOZE=5
while [ $x -le 5 ]
do
  
 ME=`basename $0`;
LOCK="/var/lock/coin_meta_ncashbtc.sh.LCK";
exec 8>$LOCK;

if flock -n -e 8; then :
  echo "Welcome $x times"

else
echo "Can't get file lock, Previos method is already running";
exit 1;
fi

  sleep ${SNOOZE}
  x=$(( $x + 5 ))
done

