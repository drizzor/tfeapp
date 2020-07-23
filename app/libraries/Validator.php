<?php

class Validator extends Controller
{
    private $data;
    protected $errors = [];
    protected $filename;

    /**
     * @param array $data
     * @return array|bool
     */
    public function validates(array $data)
    {
        $this->errors = [];
        $this->filename = "";
        $this->data = $data;
    }

    protected function validate(string $field, string $method, ...$parameters)
    {
        if(!isset($this->data[$field]))
        {
            // die("<b>ERREUR :</b> Le champ '$field' a été supprimé!? Il s'agit probablement d'une mauvaise manipulation... <br><br> <A HREF='../'>Accueil</A> <br> <A HREF='mailto:contact@kevinmary.be'>Signaler</a>");
            // return false;
            unset($this->data[$field]); // --> Mauvaise idée, si l'utilisateur malicieux supprime un champ le site crash
        }
        else
        {   
            // $this->data[$field] = trim($this->data[$field]);
            // $_POST[$field] = trim($this->data[$field]);
            return call_user_func([$this, $method], $field, ...$parameters);
        }
    }

    protected function required(string $field, bool $isArray = false): bool 
    {
        $error = false;
        if(!$isArray){
            if(empty($this->data[$field]))
            {
                $this->errors[$field] = "Le champ est obligatoire !";
                return false;
            }
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if(empty($this->data[$field][$i]))
                {
                    $this->errors[$field][$i] = "Le champ est obligatoire !";
                    $error = true;
                }
            }
        }
        if($error) return false;
        return true;
    }

    protected function requiredFile(string $field): bool 
    {
        if(empty($this->data[$field]['name']))
        {
            $this->errors[$field] = "Veuillez joindre le fichier !";
            return false;
        }
        return true;
    }

     /*------------------- STRING SIZE ----------------------------*/

     protected function minLength(string $field, int $lenght, bool $isArray = false): bool
     {
         if(!$isArray) 
         {
             if(mb_strlen(trim($this->data[$field])) < $lenght){
                 $this->errors[$field] = "Le champ doit avoir plus de $lenght caractères";
                 return false;
             }            
         }
         else
         {
             for($i = 0; $i < count($this->data[$field]); $i++)
             {
                 if((mb_strlen(trim($this->data[$field][$i])) < $lenght)){
                     $this->errors[$field][$i] = "Le champ doit avoir plus de $lenght caractères";
                     return false;
                 } 
             }                       
         }
         return true;
     }

    protected function maxLength(string $field, int $lenght, bool $isArray = false): bool
    {
        if(!$isArray)
        {
            if(mb_strlen($this->data[$field]) > $lenght){
                $this->errors[$field] = "Le champ doit avoir moins de $lenght caractères";
                return false;
            }            
        }
        else
        {
            for($i = 0; $i < count($this->data[$field]); $i++)
            {
                if((mb_strlen($this->data[$field][$i]) > $lenght)){
                    $this->errors[$field][$i] = "Le champ doit avoir moins de $lenght caractères";
                    return false;
                } 
            }                       
        }
        return true;
    } 

    /*--------------------- NUMBER SIZE --------------------------*/

    protected function minNumber(string $field, $min, bool $isArray = false): bool 
    {
        if(!$isArray){
            // if($this->data[$field] === 0) $this->data[$field] = 000;
            if($this->data[$field] < $min){
                $this->errors[$field] = "Le champ doit être supérieur ou égal à $min";
                return false;
            } 
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if($this->data[$field][$i] < $min){
                    $this->errors[$field][$i] = "Le champ doit être supérieur ou égal à $min";
                    return false;
                } 
            }
        }
        
        return true;
    }

    protected function maxNumber(string $field, $max, bool $isArray = false): bool 
    {
        if(!$isArray){
            if($this->data[$field] > $max){
                $this->errors[$field] = "Le champ doit être inférieur à $max";
                return false;
            }
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if($this->data[$field][$i] > $max){
                    $this->errors[$field][$i] = "Le champ doit être inférieur à $max";
                    return false;
                }
            }
        }
        
        return true; 
    }

    /**
     * Number is greater than...
     */
    protected function numberGT(string $field, string $field_compare): bool 
    {
        if($this->data[$field] <= $this->data[$field]){
            $this->errors[$field] = "Le prix doit être strictement supérieur au prix d'acquisition";
            return false;
        }
        return true;
    }

    /*--------------------- PASSWORD EQUIV --------------------------*/

    protected function passwordEqual(string $field): bool
    {
        $this->userModel = $this->model('User');

        if(!$this->userModel->passwordVerify($_SESSION['user_id'], $this->data[$field]))
        {
            $this->errors[$field] = "Ce n'est pas votre mot de passe !";
            return false;
        }
        return true; 
    }

    protected function equal(string $confirm_field, string $field): bool
    {
        if($this->data[$field] != $this->data[$confirm_field])
        {
            $this->errors[$confirm_field] = "Les mots de passes ne correspondent pas !";
            return false;
        }
        return true;
    }

    // ------------------------ FILTER -----------------------------

    protected function filterEmail(string $field): bool
    {
        if(!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[$field] = "Veuillez entrer un format d'email valide !";
            return false;
        } 
        return true;
    }

    protected function filterFieldText(string $field): bool
    {
        if(!preg_match("/^[\w &âêîôûéèçàïäüù!?\(\)\.\,\;\:\'-]*$/i", $this->data[$field]))
        {
            $this->errors[$field] = "Les caractères spéciaux sont interdit !";
            return false;    
        }
        return true; 
    }

    protected function filterFieldUsername(string $field): bool
    {
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->data[$field]))
        {
            $this->errors[$field] = "Les espaces et caractères spéciaux sont interdit !";
            return false;    
        }
        return true; 
    }

    protected function filterFieldName(string $field): bool
    {
        if(!preg_match("/^[a-zA-Zâêîôûéèçàïäüùç\- ]*$/", $this->data[$field]))
        {
            $this->errors[$field] = "Les caractères spéciaux et les chiffres sont interdit !";
            return false;    
        }
        return true; 
    }

    protected function filterFieldPhone(string $field): bool
    {
        if(!preg_match("/^[0-9 ]*$/", $this->data[$field]))
        {
            $this->errors[$field] = "Veuillez indiquer uniquement des chiffres !";
            return false;    
        }
        return true; 
    }

    protected function filterIp(string $field): bool 
    {
        if(!filter_var($this->data[$field], FILTER_VALIDATE_IP))
        {
            $this->errors[$field] = "Adresse IP non valide !";
            return false;    
        }
        return true;
    }

    protected function isNumber(string $field, bool $isArray = false): bool  //   /^\d+(.\d{1,2})?$/
    {
        if(!$isArray){
            if(!preg_match("/^\d+(\.\d{1,2})?$/", $this->data[$field])){
                $this->errors[$field] = "Uniquement des chiffres et le POINT comme séparateur décimal (maximum deux chiffres décimaux)";
                return false;
            }
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if(!preg_match("/^\d+(\.\d{1,2})?$/", $this->data[$field][$i])){
                    $this->errors[$field][$i] = "Uniquement des chiffres et le POINT comme séparateur décimal (maximum deux chiffres décimaux)";
                    return false;
                }
            }
        }
        return true;
    }

    // ------------------------ DATES -------------------------------
    
    public function date(string $field): bool
    {
        // Renvoie une date si la méthode y parvient SINON false
        if(DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false)
        {
            $this->errors[$field] = "La date ne semble pas valide";
            return false;
        }
        return true;
    }

    // ------------------------ FIND ELEMENT & UNICITY ------------------------

    protected function findUserByEmail(string $field): bool
    {
        $this->userModel = $this->model('User');
        if($this->userModel->findUserByEmail($this->data[$field]))
        {
            $this->errors[$field] = "Cet email est déjà utilisé !";
            return false;
        }
        return true;
    }

    protected function findUserByUsername(string $field): bool
    {
        $this->userModel = $this->model('User');
        if($this->userModel->findUserByUsername($this->data[$field]))
        {
            $this->errors[$field] = "Ce nom d'utilisateur est déjà utilisé !";
            return false;
        }
        return true;
    }

    protected function findCustomerByName(string $field): bool 
    {
        $this->customerModel = $this->model('Customer');
        if($this->customerModel->findByName($this->data[$field]))
        {
            $this->errors[$field] = "Ce nom est déjà utilisé !";
            return false;
        }
        return true;
    }

    protected function findAttempt(string $field): bool
    {
        $this->userModel = $this->model('User');
        if($this->userModel->findAttempt($this->data[$field]))
        {
            $this->errors[$field] = "Cette IP est déjà dans la  liste !";
            return false;
        }
        return true;
    }

    protected function findCategoryByName(string $field): bool 
    {
        $this->categoryModel = $this->model('Category');
        if($this->categoryModel->findCategoryByName($this->data[$field]))
        {
            $this->errors[$field] = "Ce nom de catégorie est déjà utilisée !";
            return false;
        }
        return true;
    }

    protected function findSupplierByName(string $field): bool 
    {
        $this->supplierModel = $this->model('Supplier');
        if($this->supplierModel->findSupplierByName($this->data[$field]))
        {
            $this->errors[$field] = "Ce nom de fournisseur est déjà utilisé !";
            return false;
        }
        return true;
    }

    protected function findConstructionByName(string $field): bool 
    {
        $this->constructionModel = $this->model('Construction');
        if($this->constructionModel->findConstructionByName($this->data[$field]))
        {
            $this->errors[$field] = "Ce nom de chantier est déjà utilisé !";
            return false;
        }
        return true;
    }

    protected function findCityByName(string $field): bool 
    {
        $this->supplierModel = $this->model('City');
        $temp = explode(' (', $this->data[$field]);
        if(!$this->supplierModel->findCityByName($temp[0]))
        {
            $this->errors[$field] = "Ville non trouvée !";
            return false;
        }
        return true;
    }

    /**
     * Permet de vérifier l'existance du niveau utilisateur sélectionné
     */
    protected function findLevelById(string $field): bool 
    {
        $this->userModel = $this->model('User');
        if(!$this->userModel->findLevelById($this->data[$field]))
        {
            $this->errors[$field] = "Ce niveau n'existe pas !";
            return false;
        }
        return true;
    }

    /**
     * Permet de vérifier l'existance du chantier sélectionné
     */
    protected function findConstructionById(string $field): bool 
    {
        $this->constructionModel = $this->model('Construction');
        if(!$this->constructionModel->findConstructionById($this->data[$field]))
        {
            $this->errors[$field] = "Le chantier sélectionné n'existe pas !";
            return false;
        }
        return true;
    }

    /**
     * Permet de vérifier l'existance du fournisseur sélectionné
     */
    protected function findSupplierById(string $field): bool 
    {
        $this->supplierModel = $this->model('Supplier');
        if(!$this->supplierModel->findSupplierById($this->data[$field]))
        {
            $this->errors[$field] = "Le fournisseur sélectionné n'existe pas !";
            return false;
        }
        return true;
    }

    /**
     * Permet de vérifier l'existance de la catégorie sélectionnée
     */
    protected function findCategoryById(string $field, bool $isArray = false): bool 
    {
        $this->categoryModel = $this->model('Category');
        if(!$isArray){
            if(!$this->categoryModel->findCategoryById($this->data[$field]))
            {
                $this->errors[$field] = "La catégorie sélectionnée n'existe pas !";
                return false;
            }
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if(!$this->categoryModel->findCategoryById($this->data[$field][$i]))
                {
                    $this->errors[$field][$i] = "La catégorie sélectionnée n'existe pas !";
                    return false;
                }
            }
        }
        return true;
    }

    protected function existingCustomer(string $field): bool 
    {
        $this->customerModel = $this->model('Customer');

        $temp = explode(']', $this->data[$field]);
        $temp = str_replace("[", "", $temp);

        if(!$this->customerModel->findById($temp[0]))
        {
            $this->errors[$field] = "Ce client n'existe pas !";
            return false;
        }
        return true;
    }

    protected function existingTax(string $field, bool $isArray = false): bool 
    {
        $this->invoiceModel = $this->model('Invoice');
        if(!$isArray){
            if(!$this->invoiceModel->findTaxByAmount($this->data[$field]))
            {
                $this->errors[$field] = "Taux inexistant (0, 6, 12 ou 21) !";
                return false;
            }
        }
        else{
            for($i = 0; $i < count($this->data[$field]); $i++){
                if(!$this->invoiceModel->findTaxByAmount($this->data[$field][$i]))
                {
                    $this->errors[$field][$i] = "Taux inexistant (0, 6, 12 ou 21) !";
                    return false;
                }
            }
        }
        return true;
    }

    // ---------------------- FILE ----------------------------  

    protected function fileSize(string $field, int $maxSize = 10000000): bool 
    {
        $size = $this->data[$field]['size'];
        if($size > $maxSize){
            $this->errors[$field] = 'Le fichier ne doit pas dépasser '. $maxSize / 1000000 .' MB !';
            return false;
        }
       return true;
    }

    protected function fileExtension(string $field): bool 
    {
        $extension = pathinfo($this->data[$field]['name'], PATHINFO_EXTENSION);

        if($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif"){
            $this->errors[$field] = 'Les fichiers autorisés sont : .jpg, .jpeg, .png, .gif !';
            return false;
        }
        return true;
    }
    
    protected function fileExtensionPDF(string $field): bool 
    {
        $extension = pathinfo($this->data[$field]['name'], PATHINFO_EXTENSION);

        if($extension != "pdf"){
            $this->errors[$field] = 'Les fichiers autorisés sont : .pdf !';
            return false;
        }
        return true;
    }

    protected function filterZipcode(string $field): bool 
    {
        if(!preg_match("/[a-zA-Z0-9 -{0,}]/i", $this->data[$field]))
        {
            $this->errors[$field] = "Format CP invalide";
            return false;    
        }
        return true; 
    }
}