 #!/bin/sh
 #
 #

COMMAND_1="/usr/bin/php /var/www/html/custom_cornjobs/market_prices_socket/market_prices_socket.php"

 x=0
 SNOOZE=1
while [ $x -le 60 ]
do
  echo "Welcome $x times"
  ${COMMAND_1}
  sleep ${SNOOZE}
  x=$(( $x + 1 ))
done