<?php

class CategoriesValidator extends Validator 
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
        $this->validate('name', 'filterFieldName');
        $this->validate('name', 'findCategoryByName');    
        $this->validate('name', 'required');   
        
        if(!empty($data['description'])){
            $this->validate('description', 'minLength', 7);
            $this->validate('description', 'maxLength', 255);
            $this->validate('description', 'filterFieldText');
        }        

        return $this->errors;
    }    
}