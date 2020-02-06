#!/bin/bash

# php -S 127.0.0.1:8000 -t .

php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -S 127.0.0.1:8000 -t .
