#!/bin/sh
chown -R "$SFTP_USER:$SFTP_USER" /home/"$SFTP_USER"/upload
chmod -R 755 /home/"$SFTP_USER"/upload

exec /entrypoint "$@"
