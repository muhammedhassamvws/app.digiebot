 #!/bin/sh
 #
 #

  #mongodump --db binance --out /home/digiebot/public_html/backups/mongobackups/`date +"%m-%d-%y"`
  #rm -rf /home/digiebot/public_html/backups/mongobackups/`date -d '-2 day' '+%m-%d-%y'`



#  mongodump  --collection coin_meta_history --db binance --out /home/digiebot/public_html/backups/mongobackups/coin_meta_history

 mongodump  --collection users --db binance --out /home/digiebot/public_html/backups/mongobackups/users

 