# TestW

#Demarrage de docker :
	docker-compose up

Si composer a mal lancé mysqli je dois ouvrir le php container avec un terminal et run :
	docker-php-ext-install mysqli
	apachectl restart
#https://stackoverflow.com/questions/46879196/mysqli-not-found-dockerized-php

Si la db doit etre re-importer : MYSQL_DATABASE.sql

#Navigateur : http://localhost:8000/index.php

Les logins/passwords sont en db
