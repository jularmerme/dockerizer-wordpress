#/bin/bash

if [ -z "$REMOTE_FS_USER" ]; then
    echo "Plugin Sync Failed: REMOTE_FS_USER needs to be defined" >&2
    exit 1
fi
if [ -z "$REMOTE_FS_PASS" ]; then
    echo "Plugin Sync Failed: REMOTE_FS_PASS needs to be defined" >&2
    exit 1
fi
if [ -z "$REMOTE_FS_HOST" ]; then
    echo "Plugin Sync Failed: REMOTE_FS_HOST needs to be defined" >&2
    exit 1
fi
if [ -z "$REMOTE_FS_WP_PATH" ]; then
    echo "Plugin Sync Failed: REMOTE_FS_WP_PATH needs to be defined" >&2
    exit 1
fi

echo "Starting Plugin Sync"
sshpass -p "$REMOTE_FS_PASS" \
        rsync -a -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" \
        $REMOTE_FS_USER@$REMOTE_FS_HOST:$REMOTE_FS_WP_PATH/wp-content/plugins/ wp-content/plugins
echo "Plugin Sync Finished"
