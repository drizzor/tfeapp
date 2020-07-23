<?php

class purchasesValidator extends Validator 
{
    /**
     * Permet pour chaque champ de définir le type de vérification souhaitée
     * @param array $data 
     * @return array|bool
     */
    public function validates(array $data)
    {
        parent::validates($data);

        $this->validate('construction', 'findConstructionById');

        $this->validate('supplier', 'findSupplierById');        

        $this->validate('invoiceNo', 'minLength', 2);
        $this->validate('invoiceNo', 'maxLength', 20);
        $this->validate('invoiceNo', 'required');

        $this->validate('invoiceDate', 'date');

        if(isset($data['invoiceFile']['name']) && !empty($data['invoiceFile']['name'])){
            $this->validate('invoiceFile', 'fileSize');
            $this->validate('invoiceFile', 'fileExtensionPDF');            
        }
        else{
            $this->validate('invoiceFile', 'requiredFile');
        }
   
        
        $this->validate('product', 'minLength', 2, true);
        $this->validate('product', 'maxLength', 20, true);
        $this->validate('product', 'required', true);

        $this->validate('category', 'findCategoryById', true);

        $this->validate('qty', 'minNumber', 1, true);
        $this->validate('qty', 'maxNumber', 1000, true);
        $this->validate('qty', 'required', true);

        $this->validate('price', 'minNumber', 0.1, true);
        $this->validate('price', 'maxNumber', 30000, true);
        $this->validate('price', 'isNumber', true);
        $this->validate('price', 'required', true);

        $this->validate('tax', 'minNumber', 0, true);
        $this->validate('tax', 'maxNumber', 30, true);
        $this->validate('tax', 'isNumber', true);
        $this->validate('tax', 'required', true);

        return $this->errors;
    }
}