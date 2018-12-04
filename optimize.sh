#!/usr/bin/env bash
php think clear
php think optimize:route
php think optimize:autoload
php think optimize:config
# php think optimize:schema

composer dumpautoload -o
