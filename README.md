# Docker-WAF
This repository contains a Docker-compose project that contains apache2 server, db server, and phpMyAdmin server that manage db throw it.And a reverse proxy server(WAF server) to the apache2 where the web application host there. In this project we use one of the most powerful open source WAF policies supported by OWASP community it is Modsecurity
First install docker
On linux: $apt install docker.io docker-compose
Second:
Create a directory for the whole project to work with:
use: $mkdir <directory-name>
Before using the docker-compose there are two steps that must be done:
Create and configure the docker-compose.yml and Dockerfile.
