Coffee Task - course project

This application was developed using PHP 8.1, used to start nginx.
Create file .env and fill in the keys.
Install composer file and upload database in mySql.

Open container mysql -> cd docker-entrypoint-initdb.d/
mysql -u root -p coffee_task < /docker-entrypoint-initdb.d/coffee_dump.sql
