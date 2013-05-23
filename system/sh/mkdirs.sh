cd /home/webcam/

YEAR=`date +%Y`
MONTH=`date +%m`
DAY=`date +%d`

if [ ! -d /home/webcam/www/webcam/${YEAR}/${MONTH}/${DAY} ]; then
	mkdir -p /home/webcam/www/webcam/${YEAR}/${MONTH}/${DAY}
fi	

