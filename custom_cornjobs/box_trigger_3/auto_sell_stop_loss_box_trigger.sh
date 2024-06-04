 #!/bin/sh
 #
 #


COMMAND_1="/usr/local/bin/php -f /home/digiebot/public_html/app.digiebot.com/index.php admin box_trigger_3 run_Box_Trigger_3_auto_stop_loss_sell_by_cron_job"

x=0
SNOOZE=5
while [ $x -le 55 ]
do
   echo "Welcome $x times"

SCRIPT=`basename $0` 
LOCK="/var/lock/${SCRIPT}.LCK";    # get absolute path to the script itself
exec 6< "$LOCK"        # open bash script using file descriptor 6
flock -n -e 6 || { echo "ERROR: script is already running" && exit 1; }   # lock file descriptor 6 OR show error message if script is already running
${COMMAND_1}
echo "Run your single instance code here"

  sleep ${SNOOZE}
  x=$(( $x + 5 ))
done