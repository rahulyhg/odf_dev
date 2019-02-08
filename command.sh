#!/bin/bash

apt-get update

apt-get -y install git-core

apt update

apt install software-properties-common

apt-add-repository ppa:ansible/ansible

apt update

apt -y install ansible

apt-get update

apt-get -y install mysql-server

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar

chmod +x wp-cli.phar

mv wp-cli.phar /usr/local/bin/wp
