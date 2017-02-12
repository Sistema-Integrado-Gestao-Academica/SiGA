#!/usr/bin/env bash

DB_USER=${DB_USER:-root}

mysql -u $DB_USER -e "DROP DATABASE IF EXISTS siga_test;"

mysql -u $DB_USER -e "CREATE DATABASE siga_test;"

CI_ENV=testing

php index.php utils migrate #Run migration file to create your tables and default user

# php index.php Seeder seed #Add dummy data to your database for purpose of testing

cd application/tests/

phpunit --coverage-text

eval "cd ../..; exit $?"