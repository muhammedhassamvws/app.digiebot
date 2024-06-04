 #!/bin/sh
 #
 #

 
COMMAND_1="/usr/bin/php -f /var/www/html/index.php admin cronjob_reporting index"

x=1

while [ $x -le 1 ]
do
   echo "Welcome $x times"

SCRIPT=`basename $0` 
LOCK="/var/lock/${SCRIPT}.LCK";    # get absolute path to the script itself
exec 6< "$LOCK"        # open bash script using file descriptor 6
flock -n -e 6 || { echo "ERROR: script is already running" && exit 1; }   # lock file descriptor 6 OR show error message if script is already running
${COMMAND_1}
echo "Run your single instance code here"
  x=$(( $x + 1 ))
done