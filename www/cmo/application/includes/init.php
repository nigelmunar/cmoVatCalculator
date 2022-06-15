<?php
    declare(strict_types = 1);


    require_once ROOT_PATH . 'application/factories/LoggingDatabaseFactory.php';
    require_once ROOT_PATH . 'application/factories/ErrorDatabaseFactory.php';
    require_once ROOT_PATH . 'application/tools/generalFunctions.php';
  
    $loggingDB = \LoggingDatabaseFactory::create();
    $errorDB = \ErrorDatabaseFactory::create();      
    
    
