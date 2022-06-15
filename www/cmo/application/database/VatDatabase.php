<?php

    declare(strict_types = 1);

use Entities\Vat;

    require_once ROOT_PATH . 'application/tools/filterFunctions.php';
    require_once ROOT_PATH . 'application/tools/dateFunctions.php';
    require_once ROOT_PATH . 'application/utilities/RedisCacher.php';
    require_once ROOT_PATH . 'application/entities/Vat.php';
    require_once ROOT_PATH . 'application/logging/FileLogger.php';

    class VatDatabase
    {
        private $pdo;
        private $vat = [];
        private $vatCount = [];
        private $vatResults = [];

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }


        public function getVatHistory() : array
        {
            $stmt = $this->pdo->prepare('
                SELECT `vat_history_id`, `vat_history_code`, `ex_vat`, `inc_vat`, `vat_rate`
                FROM `vat_history`
                WHERE `live` = 1');

            $stmt->execute();

            $vatHistory = [];

            while($row = $stmt->fetch())
            {
                $vat = new Vat();

                $vat->setVatID((int)$row['vat_history_id']);
                $vat->setVatCode((string)$row['vat_history_code']);
                $vat->setExVat((string)$row['ex_vat']);
                $vat->setIncVat((string)$row['inc_vat']);
                $vat->setVatRate((string)$row['vat_rate']);

                $vatHistory[] = $vat;
            }

            return $vatHistory;
        }


        public function getVatList(int $start, int $length, array $sortOrder = []) : array
        {
    
            $unfilteredCount = $this->getVatHistoryCount();
            $filteredCount = $this->getVatHistoryCount();

            $orderString = '';

            for($i=0; $i < count($sortOrder); $i++)
            {
                $orderPart = $this->getOrderStringPart($sortOrder[$i]);
                
                if(strlen($orderString) > 0 && strlen($orderPart) > 0)
                {
                    $orderString .= ', ';
                }

                $orderString .= $orderPart;
            }

            if(strlen($orderString) === 0)
            {
                $orderString = '`vat_history_id` ASC';
            }

            $stmt = $this->pdo->prepare('
                SELECT `vat_history_id`, `vat_history_code`, `ex_vat`, `inc_vat`, `vat_rate`
                FROM `vat_history`
                WHERE `live` = 1
                ORDER BY ' . $orderString . '
                LIMIT :offset, :length');


            $stmt->bindValue(':offset', $start,  \PDO::PARAM_INT);
            $stmt->bindValue(':length', $length, \PDO::PARAM_INT);

            $stmt->execute();

            $results = [ 
                'recordsTotal' => $unfilteredCount,
                'recordsFiltered' => $filteredCount,
                'data' => [] 
            ];

            while($row = $stmt->fetch())
            {
                $results['data'][] = [ 
                    'id'            => (int)$row['vat_history_id'], 
                    'code'          => (string)$row['vat_history_code'], 
                    'exVat'         => (string)$row['ex_vat'], 
                    'incVat'        => (string)$row['inc_vat'],
                    'vatRate'       => (string)$row['vat_rate']
                ];
            }

            return $results;

        }

        public function saveVat(\Entities\Vat $vat) : \Entities\Vat
        {
            $stmt = $this->pdo->prepare('
                INSERT INTO `vat_history` (`ex_vat`, `inc_vat`, `live`, `vat_rate`)
                VALUES (:ex_vat, :inc_vat, 1, :vat_rate)');

            $stmt->bindValue(':ex_vat', $vat->getExVat(), PDO::PARAM_STR);
            $stmt->bindValue(':inc_vat', $vat->getIncVat(), PDO::PARAM_STR);
            $stmt->bindValue(':vat_rate', $vat->getVatRate(), PDO::PARAM_STR);

            $stmt->execute();

            $vatID = (int)$this->pdo->lastInsertId();

            $vat->setVatID($vatID);


            $stmt = $this->pdo->prepare('
                SELECT `vat_history_code`
                FROM `vat_history`
                WHERE `vat_history_id` = :vat_history_id');
            
            $stmt->bindValue(':vat_history_id', $vat->getVatID(), \PDO::PARAM_INT);
            $stmt->execute();

            if($row = $stmt->fetch())
            {
                $vatCode = (string)$row['vat_history_code'];

                $vat->setVatCode($vatCode);
            }

            return $vat;

        }

        public function getVatHistoryCount() : int
        {
    
            $stmt = $this->pdo->prepare('
                SELECT COUNT(1) AS `vat_count`
                FROM `vat_history`
                WHERE `live` = 1');
                
            $stmt->execute();

            if($row  = $stmt->fetch())
            {
                return (int)$row['vat_count'];
            }

        }

        private function getOrderStringPart(array $orderPart) : string
        {
            $orderString = '';

            if(count($orderPart) === 2)
            {
                switch($orderPart[0])
                {
                    case 'id':

                        $orderString .= '`vat_history_id`';

                        break;

                    case 'ex_vat':

                        $orderString .= '`ex_vat`';

                        break;

                    case 'inc_vat':

                        $orderString .= '`inc_vat`';

                        break;

                    case 'vat_rate':

                        $orderString .= '`vat_rate`';

                        break;
                    
                }

                if(strlen($orderString) > 0)
                {
                    if($orderPart[1] === 'asc')
                    {
                        $orderString .= ' ASC';
                    }
                    else
                    {
                        $orderString .= ' DESC';
                    }
                }
            }


            return $orderString;
        }

        public function clearHistory() : void
        {
            $stmt = $this->pdo->prepare('
                UPDATE `vat_history` 
                SET `live` = 0
                WHERE `live` = 1');

            $stmt->execute();
        }

        public function getCSV() : void
        {
            $stmt = $this->pdo->prepare('
                SELECT `vat_history_id`, `vat_history_code`, `ex_vat`, `inc_vat`, `vat_rate`
                FROM `vat_history`
                WHERE `live` = 1');

            $stmt->execute();

            $vatHistory = [];

            while($row = $stmt->fetch())
            {
                $vatHistory[] = [(int)$row['vat_history_id'], (string)$row['vat_history_code'], (string)$row['ex_vat'], (string)$row['inc_vat'], (string)$row['vat_rate']];
            }

            $headers = ['ID', 'CODE', 'EXCLUDING VAT', 'INCLUDING VAT', 'VAT RATE'];

            $filename = 'vat_calculator.csv';

            $delimiter = ',';

            $fp = fopen('php://output', 'w');

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=' . $filename);

            fputcsv($fp, $headers, $delimiter);

            for($i = 0; $i < count($vatHistory); $i++)
            {
                fputcsv($fp, $vatHistory[$i], $delimiter);
            }

            fclose($fp);

            exit();

        }
    }