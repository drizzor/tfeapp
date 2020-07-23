<?php

class InvoicesValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {        
        parent::validates($data);

        $this->validate("customer", 'existingCustomer');
        $this->validate("customer", 'required');

        $this->validate('description', 'minLength', 2, true);
        $this->validate('description', 'maxLength', 20, true);
        $this->validate('description', 'required', true);

        $this->validate('qty', 'minNumber', 1, true);
        $this->validate('qty', 'maxNumber', 1000, true);
        $this->validate('qty', 'required', true);

        $this->validate('price', 'minNumber', 1, true);
        $this->validate('price', 'maxNumber', 10000, true);
        $this->validate('price', 'isNumber', true);
        $this->validate('price', 'required', true);

        $this->validate('tax', 'existingTax', true);
        $this->validate('tax', 'required', true);

        if(!empty($data['comment'])){
            $this->validate('comment', 'minLength', 5);
            $this->validate('comment', 'maxLength', 255);
        }

        return $this->errors;
    }
}