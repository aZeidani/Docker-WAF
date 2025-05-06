# Docker-WAF
This repository contains a Docker-compose project that contains apache2 server, db server, and phpMyAdmin server that manage db throw it.And a reverse proxy server(WAF server) to the apache2 where the web application host there. In this project we use one of the most powerful open source WAF policies supported by OWASP community it is Modsecurity
First install docker
On linux: $apt install docker.io docker-compose
Second:
Create a directory for the whole project to work with:
use: $mkdir <directory-name>
install the github repo to your created direcotry:
user:$git clone https://github.com/aZeidani/Docker-WAF.git
Now run the docker-compose command that will execute the instructions included in docker-compose.yml
use:$sudo docker-compose up -d (#-d for set to background process.)

