 #!/bin/sh
 #
 #

 
COMMAND_1="/usr/bin/php -f /var/www/html/index.php admin trending_script five_second_trending_cronjob"
    

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