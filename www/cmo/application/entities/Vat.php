<?php
    declare(strict_types = 1);

    namespace Entities;

    class Vat implements \JsonSerializable 
    {
        private $_vatID; //int
        private $_vatCode; //string
        private $_exVat; //string
        private $_incVat; //string
        private $_vatRate; //string

        
        public function getVatID() : int
        {
            return $this->_vatID;
        }
        
        public function setVatID(int $value) : void
        {
            $this->_vatID = $value;
        }
        
        
        public function getVatCode() : string
        {
            return $this->_vatCode;
        }
        
        public function setVatCode(string $value) : void
        {
            $this->_vatCode = $value;
        }
        
        
        public function getExVat() : string
        {
            return $this->_exVat;
        }
        
        public function setExVat(string $value) : void
        {
            $this->_exVat = $value;
        }
        
        
        public function getIncVat() : string
        {
            return $this->_incVat;
        }
        
        public function setIncVat(string $value) : void
        {
            $this->_incVat = $value;
        }
        
        
        public function getVatRate() : string
        {
            return $this->_vatRate;
        }
        
        public function setVatRate(string $value) : void
        {
            $this->_vatRate = $value;
        }
        
        
        


        public function jsonSerialize() : array
        {
            return [
                'id' => $this->_vatID,
                'code' => $this->_vatCode,
                'exVat' => $this->_exVat,
                'incVat' => $this->_incVat,
                'vatRate' => $this->_vatRate,
            ];
        }


        public function jsonDeserialize(string $jsonString) : void
        {
            $json = json_decode($jsonString, true);

            $this->_vatID = $json['id'];
            $this->_vatCode = $json['code'];
            $this->_exVat = $json['exVat'];
            $this->_incVat = $json['incVat'];
            $this->_vatRate = $json['vatRate'];
       
        }
    }