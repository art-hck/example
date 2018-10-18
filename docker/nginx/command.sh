#!/bin/bash

echo "Setup nginx config...";
EXT=.dist

ls /etc/nginx/conf.d/*${EXT} | while read FILEPATH
do
    NAME=${FILEPATH%${EXT}}
    if [ ! -f ${NAME} ]; then
        echo "Generate ${NAME}"
        cp ${NAME}{${EXT},}
        envsubst '$$NGINX_PORT $$NGINX_HOST' < /etc/nginx/conf.d/main.conf.dist > /etc/nginx/conf.d/main.conf
        
    fi
done

exec nginx -g 'daemon off;'
