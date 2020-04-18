[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3286c50ed66846cc9a7cddc8e7b961fe)](https://www.codacy.com/manual/d.males/p6_snow?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=damirmales/p6_snow&amp;utm_campaign=Badge_Grade)

Environment used during development
Symfony 4.4.4
Composer 1.9.3
Bootstrap 4.4.1
jQuery 3.3.1
PHPUnit 7.5.1
XampServer 7.3.14
Apache 2.4.4
PHP 7.3.1
MariaDB 10.4.11
Installation
Clone or download the GitHub repository in the desired folder: :
   https://github.com/damirmales/p6_snow.git

Configure your environment variables such as connection to the database in the .env file
 
Download and install the back-end dependencies of the project with
Composer :composer install
 
To create the database, type the command below:  
 php bin/console doctrine:database:create 
 
Create the different tables of the database by applying migrations:
 php bin/console doctrine:migrations:migrate 
 
Fill the Figure table with UserFixtures.php : 
php bin/console doctrine:fixtures:load --group=UserFixtures

Fill the Figure table with  FigureFixtures.php : 
php bin/console doctrine:fixtures:load --group=FigureFixtures
