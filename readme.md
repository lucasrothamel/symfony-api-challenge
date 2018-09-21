
# Symfony API Coding Challenge

by Lucas Rothamel <lucas@rothamel.info>

#### Design:
* symfony 4.1 using MySQL, Doctrine and FOSRestBundle
* not using /api prefix, as complete project only includes this api
* using DataFixtures to provide test data
* using Codeception api test suite for testing

#### Setup: 
* checkout the devilbox repos from [http://devilbox.org/]
* checkout this repository, and place it by simlink into the `data/www` repository
* add an entry to `/etc/hosts` `127.0.0.1 symfony-api-challenge.loc` unless using devilboy dns
* Run by `docker-compose up` in devilbox directory
* Open the php docker shell by running `shell.sh` from the devilbox direcory
* copy `.env.dist` to `.env`, and making adjustments as necessary
* run ``setup_mysql_in_devilbox.sh`` to setup the database
* run the `reset_db.sh` to reset the database (by dropping), and run all fixtures on the empty database

#### Documentation:
* Uses NelmioApiDocBundle and Swagger annotations
* Auto-generated documentation then available at `https://symfony-api-challenge.loc/doc`

#### Tests:
* Tests can be found in the `tests\api` folder 
* can be run from the php docker shell using `vendor/bin/codecept run`

#### Todo / Known problems:
* codecept gives one deprecated error of ``Doctrine\Comon\ClassLoader``
* Swagger documentation should be fine-tuned and extended further
* User and session Management are completely missing, once added, in `JobController::postAction()`, the user id of the logged in user should be used instead of the static user id `1`