FROM mysql:5.7

ADD init.sh /docker-entrypoint-initdb.d/
ADD mysql.cnf /etc/mysql/conf.d/
RUN chmod 0644 /etc/mysql/conf.d/mysql.cnf 
