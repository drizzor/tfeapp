<?php

class WorkersValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {
        parent::validates($data);

        $this->validate('fristname', 'minLength', 2);
        $this->validate('firstname', 'maxLength', 30);
        $this->validate('fristname', 'filterFieldName');
        $this->validate('firstname', 'required');

        $this->validate('lastname', 'minLength', 2);
        $this->validate('lastname', 'maxLength', 30);
        $this->validate('lastname', 'filterFieldName');
        $this->validate('lastname', 'required');

        $this->validate('salary', 'minNumber', 1.0);
        $this->validate('salary', 'maxNumber', 500);
        $this->validate('salary', 'isNumber');
        $this->validate('salary', 'required');

        $this->validate('email', 'minLength', 7);
        $this->validate('email', 'maxLength', 50);
        $this->validate('email', 'filterEmail');
        $this->validate('email', 'required');

        return $this->errors;
    }
}