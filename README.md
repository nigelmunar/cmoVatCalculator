# cmoVatCalculator

1. Once you have downloaded the project open terminal / command line and navigate to the cmo-php-apache-docker
2. Make sure your local mysql is not running at this point ( tip to stop mysql on mac run $ sudo pkill mysql  $ sudo pkill mysqld )
3. Run command $ docker compose up --build -d
4. Check if all your docker containers are running
5. If they are running navigate to the containers and open the CLI of php71
6. run commmand $ cd cmo/application (to navigate to the projcect application folder)
7. run $ composer install (to install all the required packages)
8. go to the phpmyadmin container and click on open in browser
9. username = root, password = cmotest
10. create a schema using the query below
11. CREATE DATABASE `cmo` /*!40100 DEFAULT CHARACTER SET utf8 */;
12. Create the following tables by running the create table querys provided below
13. CREATE TABLE `error_pages` (
  `error_page_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_url` varchar(1000) NOT NULL,
  `date_time_created` datetime NOT NULL,
  PRIMARY KEY (`error_page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `errors` (
  `error_id` int(11) NOT NULL AUTO_INCREMENT,
  `error_page_id` int(11) NOT NULL,
  `ip_address_id` int(11) NOT NULL,
  `user_agent_id` int(11) NOT NULL,
  `error_code` smallint(6) NOT NULL,
  `error_message` varchar(4000) DEFAULT NULL,
  `date_time_logged` datetime NOT NULL,
  PRIMARY KEY (`error_id`),
  KEY `fk_errors_error_pages_idx` (`error_page_id`),
  CONSTRAINT `fk_errors_error_pages` FOREIGN KEY (`error_page_id`) REFERENCES `error_pages` (`error_page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `ip_addresses` (
  `ip_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(39) NOT NULL,
  PRIMARY KEY (`ip_address_id`),
  UNIQUE KEY `ip_address_UNIQUE` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `user_agents` (
  `user_agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_agent` varchar(2000) NOT NULL,
  PRIMARY KEY (`user_agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `vat_history` (
  `vat_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `vat_history_code` varchar(36) DEFAULT NULL,
  `ex_vat` decimal(11,2) NOT NULL,
  `inc_vat` decimal(11,2) NOT NULL,
  `live` tinyint(1) NOT NULL,
  `vat_rate` decimal(11,2) NOT NULL,
  PRIMARY KEY (`vat_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
14. If all the tables have been created create a trigger for the vat_history table by running the query below
15. CREATE DEFINER=`root`@`%` TRIGGER `cmo`.`vat_history_BEFORE_INSERT` BEFORE INSERT ON `vat_history` FOR EACH ROW
BEGIN
	if new.vat_history_code is null then 
    set new.vat_history_code=uuid();
    end if;
END
16. the query generates a UUID for every new insert 
17. Add a vhost to your local machined with the name cmo.local on port 127.0.0.1
18. to do this on mac go to your terminal and run $ sudo nano /etc/hosts
19. Enter your password
20. Add cmo.local on port 127.0.0.1 and save
21. Restart your containers if needed and visit cmo.local on your browser
