#!/bin/bash

PROJECT_PATH=`dirname $(readlink -f $0)`
PROJECT_NAME=${PROJECT_PATH##*/}

echo ""
echo "Envirment setup."
echo ""
read -p "DB_ROOT_PASS [new_db_root_pass]: " DB_ROOT_PASS
read -p "DB_NAME [new_db]: " DB_NAME
read -p "DB_TEST_NAME [new_test_db]: " DB_TEST_NAME
read -p "DB_USER [new_db_user]: " DB_USER
read -p "DB_PASS [new_db_pass]: " DB_PASS
read -p "APP_PATH (in containers) [/opt/$PROJECT_NAME]: " APP_PATH
read -p "NGINX_PORT [8080]: " NGINX_PORT
read -p "NGINX_HOST [localhost]: " NGINX_HOST

echo DB_ROOT_PASS=${DB_ROOT_PASS:-new_db_root_pass} > ${PROJECT_PATH}/.env
echo DB_NAME=${DB_NAME:-new_db} >> ${PROJECT_PATH}/.env
echo DB_TEST_NAME=${DB_TEST_NAME:-new_test_db} >> ${PROJECT_PATH}/.env
echo DB_USER=${DB_USER:-new_db_user} >> ${PROJECT_PATH}/.env
echo DB_PASS=${DB_PASS:-new_db_pass} >> ${PROJECT_PATH}/.env
echo APP_PATH=${APP_PATH:-/opt/${PROJECT_NAME}} >> ${PROJECT_PATH}/.env
echo NGINX_PORT=${NGINX_PORT:-8080}} >> ${PROJECT_PATH}/.env
echo NGINX_HOST=${NGINX_HOST:-localhost}} >> ${PROJECT_PATH}/.env

echo USER=`whoami` >> ${PROJECT_PATH}/.env
echo PROJECT_PATH=${PROJECT_PATH} >> ${PROJECT_PATH}/.env
