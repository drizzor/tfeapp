<?php 

class SuppliersValidator extends Validator
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
        $this->validate('name', 'filterFieldName');
        $this->validate('name', 'findSupplierByName');    
        $this->validate('name', 'required'); 

        $this->validate('city', 'findCityByName');
        $this->validate('city', 'required');

        if(!empty($data['address'])){
            $this->validate('address', 'minLength', 10);
            $this->validate('address', 'maxLength', 255);
            // $this->validate('address', 'filterFieldText');
        }

        if(!empty($data['contactName'])){
            $this->validate('contactName', 'minLength', 5);
            $this->validate('contactName', 'maxLength', 100);
            $this->validate('contactName', 'filterFieldName');
        }

        if(!empty($data['phone'])){
            $this->validate('phone', 'minLength', 8);
            $this->validate('phone', 'maxLength', 20);
            $this->validate('phone', 'filterFieldPhone');
        }

        if(!empty($data['email'])){
            $this->validate('email', 'minLength', 7);
            $this->validate('email', 'maxLength', 50);
            $this->validate('email', 'filterEmail');
        }

        return $this->errors;
    }
}