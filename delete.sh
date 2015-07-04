#!/bin/sh
BASEDIR="/var/www/data/uploads/"
DIR=""
VALID="1"
if [ $1 = "1" ]; then
	DIR="1min"
elif [ $1 = "2" ]; then
	DIR="5mins"
elif [ $1 = "3" ]; then
	DIR="1hour"
elif [ $1 = "4" ]; then
	DIR="5hours"
elif [ $1 = "5" ]; then
	DIR="12hours"
elif [ $1 = "6" ]; then
	DIR="1day"
elif [ $1 = "7" ]; then
	DIR="5days"
elif [ $1 = "8" ]; then
	DIR="30days"
else
	VALID="0"
fi

if [ $VALID = "1" ]; then
	DELPATH="$BASEDIR$DIR/"
	echo $DELPATH/*
	rm -rf $DELPATH/*
	rm -rf $DELPATH/.* #Sometime user try to up .htaccess
	date +"%D %T" > $DELPATH/runhistory.txt
	cp -f /var/www/html/log/index.html $DELPATH/
fi