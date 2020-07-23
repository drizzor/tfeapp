<?php

class ConstructionsValidator extends Validator 
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
        $this->validate('name', 'maxLength', 30);
        $this->validate('name', 'findConstructionByName');    
        $this->validate('name', 'required'); 

        $this->validate('city', 'findCityByName');
        $this->validate('city', 'required');

        if(!empty($data['address'])){
            $this->validate('address', 'minLength', 10);
            $this->validate('address', 'maxLength', 255);
        }

        $this->validate('buyingPrice', 'minNumber', 0);
        $this->validate('buyingPrice', 'maxNumber', 10000000);
        $this->validate('buyingPrice', 'isNumber');
        $this->validate('buyingPrice', 'required');

        $this->validate('surface', 'minNumber', 0);
        $this->validate('surface', 'maxNumber', 5000);
        $this->validate('surface', 'isNumber');
        $this->validate('surface', 'required');

        $this->validate('taxes', 'minNumber', 0.00);
        $this->validate('taxes', 'maxNumber', 100000);
        $this->validate('taxes', 'isNumber');
        $this->validate('taxes', 'required');

        $this->validate('estimatePrice', 'minNumber', 0);
        $this->validate('estimatePrice', 'maxNumber', 20000000);
        $this->validate('estimatePrice', 'isNumber');
        // $this->validate('estimatePrice', 'numberGT', 'buyingPrice');
        $this->validate('estimatePrice', 'required');

        $this->validate('buyingDate', 'date');
        $this->validate('buyingDate', 'required');

        if(isset($_FILES))
        {
            if(isset($data['image']['name']) && !empty($data['image']['name'])){
                $this->validate('image', 'fileSize');
                $this->validate('image', 'fileExtension');
            }
        } 
        
        if(!empty($data['comment'])){
            $this->validate('address', 'minLength', 5);
            $this->validate('address', 'maxLength', 255);
        }

        if(isset($_POST['price'])){
            $this->validate('price', 'minNumber', 20000);
            $this->validate('price', 'maxNumber', 20000000);
            $this->validate('price', 'isNumber');

            $this->validate('date', 'date');
            $this->validate('date', 'required');
        }

        return $this->errors;
    }    
}