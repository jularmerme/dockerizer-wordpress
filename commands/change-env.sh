#/bin/bash
docker-compose stop
if [ -f "envfile.txt" ] ; then
    rm envfile.txt
fi
ln -s envfile.$1.txt envfile.txt
sleep 3s
docker-compose up --build