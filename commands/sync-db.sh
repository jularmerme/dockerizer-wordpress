#/bin/bash
# Assumptions:
#   1. This is for MYSQL only
#   2. This requires the MYSQL Docker-compose block with the variables:
#      MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD and for MYSQL_HOST set its value to the output of this command:
# docker-compose run --rm mysql /sbin/ip route|awk '/default/ { print $3 }'

# How to easily sync your database to your docker virtual machine's db server:
#   1. Update the values for the following environment variables: REMOTE_DB_HOST, REMOTE_DB_NAME, REMOTE_DB_USER, REMOTE_DB_PASSWORD in this file.
#   2. Run it from the command line like so:
#      $ bash commands/sync-db.sh

# You will also need to uncomment the line in docker-compose.yml which relates to the production or staging server, depending on which your are syncing from.

docker-compose run mysql bash <<'EOF'
env | grep REMOTE
mysqldump -h $REMOTE_DB_HOST -u $REMOTE_DB_USER -p$REMOTE_DB_PASSWORD $REMOTE_DB_NAME > /tmp/remote-db.sql
mysql -h $LOCALHOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < /tmp/remote-db.sql
exit
EOF
