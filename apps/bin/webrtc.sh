#!/bin/bash
PID_FILE="/var/run/webrtc.pid"

case $1 in

  start)
        echo -n "Starting Webrtc Server "
        if [ -e ./test ]; then 
          rm "/var/run/webrtc.pid"
        fi
        php /home/ksato/Project/square/apps/bin/webrtc.php & > /dev/null
        RETVAL=$?
        echo
        ;;
  stop)
        echo -n "Shutting down Webrtc Server "
        killproc -p "$PID_FILE" 
        RETVAL=$?
        echo
        ;;
  status)
        status -p "$PID_FILE" 
        RETVAL=$?
        ;;
  *)
        echo "Usage: $(basename "$0") { start | stop | status }"
        ;;
esac

exit $RETVAL

