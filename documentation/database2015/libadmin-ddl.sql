

drop database IF EXISTS libadmin;

create database libadmin CHARACTER SET utf8;

GRANT ALL ON libadmin.* TO sbLibAdmin@'%' IDENTIFIED BY '[your password]';


