<?php
    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/VatDatabase.php';

    
    class VatDatabaseFactory
    {
        public static $vatDatabase = null;

        public static function create() : \VatDatabase
        {
            if(is_null(VatDatabaseFactory::$vatDatabase))
            {
                VatDatabaseFactory::$vatDatabase = new \VatDatabase(\PDOFactory::getConnection());
            }

            return VatDatabaseFactory::$vatDatabase;
        }

    }
