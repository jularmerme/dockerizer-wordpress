#/bin/bash

docker-compose run web bash -c 'rsync -e "ssh " -akzv  --stats --progress \
$REMOTE_FS_USER@$REMOTE_FS_HOST:$REMOTE_FS_WP_PATH/wp-content/uploads/ wp-content/uploads'
