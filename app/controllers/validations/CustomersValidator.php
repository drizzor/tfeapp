<?php

class CustomersValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {        
        parent::validates($data);

        $this->validate('name', 'minLength', 2);
        $this->validate('name', 'maxLength', 50);
        $this->validate('name', 'findCustomerByName');    
        $this->validate('name', 'required');

        if(!empty($data['tva_number'])){
            $this->validate('tva_number', 'minLength', 2);
            $this->validate('tva_number', 'maxLength', 20);
        }

        $this->validate("zipcode", 'minLength', 2);
        $this->validate("zipcode", 'maxLength', 15);
        $this->validate("zipcode", 'filterZipcode');
        $this->validate("zipcode", 'required');

        $this->validate("city", 'minLength', 2);
        $this->validate("city", 'maxLength', 30);
        $this->validate("city", 'required');

        if(!empty($data['address'])){
            $this->validate('address', 'minLength', 10);
            $this->validate('address', 'maxLength', 255);
        }

        $this->validate('country', 'minLength', 2);
        $this->validate('country', 'maxLength', 30);
        $this->validate('country', 'required');

        if(!empty($data['email'])){
            $this->validate('email', 'minLength', 7);
            $this->validate('email', 'maxLength', 50);
            $this->validate('email', 'filterEmail');
        }        

        return $this->errors;
    }
}