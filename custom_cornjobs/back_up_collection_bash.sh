#!/usr/bin/env bash
#Script for Digiebot by Rabi


COLLECTIONS=(sockets_track)
HOST="127.0.0.1"
PORT="27017"
DB="binance"

fn="backup_$(date +%Y%m%d%j%m%s)"
mkdir "/home/digiebot/public_html/backups/$fn/"

if [ -n "$1" -a "$1" = "--help" ]
then
    echo "Usage: $0 -h hostname -p port - d database -c collection"
    echo
    echo "  hostname    Address of the mongo server. Defaults to 127.0.0.1"
    echo "  port        Port of the mongo server. Defaults to 27017"
    echo "  database    Name of the database."
    echo "  collection  Name of collection to backup."
    echo 
    echo "Option -c may be specified multiple times for multiple collections."
    echo
    echo "JSON files will be created in the current directory,"
    echo "One for each collection."
    echo
    echo "Example: $0 -h 192.168.1.154 -d dev -c products -c prices -c companies"
    exit
fi

while getopts "h:p:d:c:" flag
do
    case $flag in
        h) HOST="$OPTARG" ;;
        p) PORT="$OPTARG" ;;
        d) DB="$OPTARG" ;;
        c) COLLECTIONS+=("$OPTARG") ;;
    esac
done

if [ -z "$HOST" ]
then
    echo "Please specify a host (-h). Type '$0 --help' for help."
    exit
fi

if [ -z "$PORT" ]
then
    echo "Please specify a port (-p). Type '$0 --help' for help."
    exit
fi

if [ -z "$DB" ]
then
    echo "Please specify a database (-d). Type '$0 --help' for help."
    exit
fi



if [ -z "$COLLECTIONS" ]
then
    echo "Please specify at least one collection to export (-c)."
    echo "Type '$0 --help' for help."
    exit
fi

for c in ${COLLECTIONS[@]}
do
    echo "Exporting $HOST:$PORT/$DB/$c to "$fn"/$c.json..."

    mongoexport --host $HOST --port 27017 --username binance --authenticationDatabase admin --collection $c --db $DB --out /home/digiebot/public_html/backups/"$fn"/$c.json
   
done

echo "Done."