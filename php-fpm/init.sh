#!/bin/bash
set -e

chown -R www-data: /var/www
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf
