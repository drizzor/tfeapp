<?php

class GalleriesValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {        
        parent::validates($data);

        if(isset($_POST['title']) && !empty($_POST['title'])){
            $this->validate('title', 'minLength', 2);
            $this->validate('title', 'maxLength', 30);
        }
         
        if(isset($_POST['description']) && !empty($_POST['description'])){
            $this->validate('description', 'minLength', 10);
            $this->validate('description', 'maxLength', 255);
        }
  
        $this->validate('construction', 'findConstructionById');
        
        $this->validate('image', 'fileSize');
        $this->validate('image', 'fileExtension');

        return $this->errors;
    }
}