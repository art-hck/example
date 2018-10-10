#!/bin/bash

PROJECT_PATH=`dirname $(dirname $(readlink -f $0))`

echo "Setup nginx config...";
EXT=.dist
ls ${PROJECT_PATH}/docker/nginx/conf.d/*${EXT} | while read FILEPATH
do
    NAME=${FILEPATH%${EXT}}
    echo "Generate ${NAME}"
    cp ${NAME}{${EXT},}
done

echo "Complete";