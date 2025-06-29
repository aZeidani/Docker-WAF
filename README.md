# Docker-WAF
This repository contains a Docker-compose project that contains apache2 server, db server, and phpMyAdmin server that manage db throw it.And a reverse proxy server(WAF server) to the apache2 where the web application host there. In this project we use one of the most powerful open source WAF policies supported by OWASP community it is Modsecurity
First install docker
On linux: $apt install docker.io docker-compose
Second:
Create a directory for the whole project to work with:
use:
$mkdir <directory-name>
install the github repo to your created directory:
use:
$git clone https://github.com/aZeidani/Docker-WAF.git
Now run the docker-compose command that will execute the instructions included in docker-compose.yml
use:
$sudo docker-compose up -d (#-d for set to background process.)
After everything done, now we should configure our apache2 web server.
use:
$docker exec -it <container_id> bash (this command open a bash terminal for the apache2 server itself)
now inside the bash go to (/etc/apache2/sites-available/000-default.conf)
change the DocumentRoot value to the project path on the apache server file system. like (/var/www/html/<my_project_folder>)
now lets create the .htaccess file this file is defined by apache as default file for rendering the first page route of the project. its default value is '/' we want to change it to match our homepage.
1)inside the /var/www/html directory create .htaccess file then go inside.
2)put this inside it: DirectoryIndex home.php

DataBase specifications:
1)using php-my-admin server to access our database.

