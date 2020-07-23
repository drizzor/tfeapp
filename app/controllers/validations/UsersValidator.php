<?php

class UsersValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {        
        parent::validates($data);
        if(isset($_POST))
        {
            $this->validate('username', 'minLength', 2);
            $this->validate('username', 'maxLength', 20);
            $this->validate('username', 'filterFieldUsername');
            $this->validate('username', 'findUserByUsername');    
            $this->validate('username', 'required');   
            
            $this->validate('email', 'minLength', 7);
            $this->validate('email', 'maxLength', 50);
            $this->validate('email', 'filterEmail');
            $this->validate('email', 'findUserByEmail');
            $this->validate('email', 'required');

            if((isset($data['current_password']))){
                if(!empty($data['current_password']) || !empty($data['password']) || !empty($data['confirm_password']))
                {
                    $this->validate('current_password', 'passwordEqual');
                
                    $this->validate('password', 'minLength', 7);
                    $this->validate('password', 'maxLength', 50);
                    $this->validate('password', 'required');
                    
                    $this->validate('confirm_password', 'equal', 'password');
                    $this->validate('confirm_password', 'required');
                }            
            }
            else if(isset($data['admin_set_password'])){
                if(!empty($data['admin_set_password']) || !empty($data['confirm_password']))
                {
                    $this->validate('admin_set_password', 'minLength', 7);
                    $this->validate('admin_set_password', 'maxLength', 50);
                    $this->validate('admin_set_password', 'required');
                    
                    $this->validate('confirm_password', 'equal', 'admin_set_password');
                    $this->validate('confirm_password', 'required');
                }
            }
            else
            {            
                $this->validate('password', 'minLength', 7);
                $this->validate('password', 'maxLength', 50);
                $this->validate('password', 'required');
                
                $this->validate('confirm_password', 'equal', 'password');
                $this->validate('confirm_password', 'required');
            } 
        }
        
        if(isset($_FILES))
        {
            if(isset($data['image']['name']) && !empty($data['image']['name'])){
                $this->validate('image', 'fileSize');
                $this->validate('image', 'fileExtension');
            }
        } 
        
        if(isset($data['level'])){
            $this->validate('level', 'findLevelById');
        }

        if(isset($data['ip']) && !empty($data['ip'])){
            $this->validate('ip', 'filterIp');
            $this->validate('ip', 'findAttempt');
        }

        return $this->errors;
    }
}