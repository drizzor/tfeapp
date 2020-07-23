<?php 

/**
 * Injecter la page 404
 */
function e404()
{
    require '../app/views/404.php';
    exit();
}

/**
 * Debug de variables avec un affichage plus agréable
 */
function dd(...$vars)// '...' = spread operator (permet de débug plusieur variables à la fois)
{
    foreach($vars as $var)
    {
        echo '<pre style="color: green;">';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * Sécuriser les données passée par l'ulilisateur
 */
function h(?string $value) : string
{
    if($value === null) return '';
    return htmlentities($value);
}

/**
 * Injecter des données dans les fichiers include
 */
function render(string $view, $parameters = [])
{
    extract($parameters);
    require APPROOT . "/views/inc/{$view}.php";    
}

/**
 * Rediriger l'utilisateur vers la page souhaitée
 */
function redirect(string $page)
{
    header('Location: ' . URLROOT . '/' . $page);
}

/**
 * Permet d'obtenir une réponse des serveurs Google pour le recaptcha
 */
function getCaptcha(string $secretKey)
{
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$secretKey}");
    $return = json_decode($response);
    return $return;
}

/**
 * Petite fonction permettant l'envoi d'un email basique (texte brut)
 */
function sendMail(?string $username = null, string $emailTo, ?string $message = null, ?string $var = "", string $emailFrom = MAINMAIL): bool
{
    ($username)?? "madame, monsieur";
    switch ($message) {
        case 'register_confirm':
            $message = "Bonjour $username, les administrateurs du site sont entrain de vérifier votre demande. Une réponse vous sera donnée dans les plus bref délais.";
            break;

        case 'register_validate':
            $message = "Bonjour $username, votre demande d'accès a été validée. Vous pouvez utiliser l'application via le lien suivant : " . URLROOT;
            break;    
        
        case 'register_decline':
            $message = "Bonjour $username, votre demande d'accès a été rejetée.";
            break;

        case 'register_lock':
            $message = "Bonjour $username, votre compte a été bloqué.";
            break;

        case 'register_unlock':
            $message = "Bonjour $username, votre compte a été débloqué.";
            break;

        case 'account_recover':
            $message = "Bonjour, vous avez fait une demande de récupération de compte pour le site " . SITENAME . ". Nouveau MDP : ".$var. " N'oubliez pas de le changer une fois connecté !";
            break;

        default:
            $message = "";
            break;
    }
    
    if(empty($message)) return false;
    else{
        $headers = "From: " . SITENAME . " <$emailFrom>\r\nReply-To: $emailFrom";
        mail($emailTo, SITENAME . " - Message de l'administrateur", $message, $headers);
        return true;
    }    
}

/**
 * Renommage du fichier à uploader
 */
function renameFile(string $name) 
{
    $temp  = explode(".", $name);
    return uniqid('', true) . '.' . end($temp);    
} 

/**
 * Vérifier l'existence d'un doublon
 */
function fileExist(string $name, string $directory): bool
{
    pathinfo($name,PATHINFO_EXTENSION);
    $path  = URLROOT . '/' . $directory . '/' . $name;

    if(file_exists($path)){
        return true;
    }
    return false;
}

/**
 * Tente l'envoi du fichier uploadé dans le répertoire cible
 */
function uploadFile(string $tmp_name, string $directory, string $filename): bool
{
    return move_uploaded_file($tmp_name, $directory . "/" . $filename);
}

/**
 * Supprimer un fichier de type image ou autres
 */
function removeFile(string $filename, string $directory, bool $isImage = true): bool
{
    if($isImage)
    {
        if(file_exists($directory . $filename) && $filename != 'default.png'){
            unlink($directory . $filename);
            return true;
        } 
    }
    else
    {
        if(file_exists($directory . $filename) && $filename != 'error.pdf'){
            unlink($directory . $filename);
            return true;
        } 
    }    
    return false;
}

/**
 * Permet de récupérer facilement l'id situé en URL
 */
function explodeGET(string $get): array
{
    $element = explode('/', $get);
    return $element;
}

/**
 * A partir d'un timestamp détermine si le délais est dépassé ou non
 * @param $date -> timestamp
 */
function timeLimitReached($date, $duration = 1, $choice = 'hour')
{
    if(empty($date)) return false;

    $diff = abs(time() - $date); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
    $data = [];

    if($choice === 'hour'){
        $tmp = $diff;
        $data[$choice] = floor(($tmp/60)/60);
    }
    
    if($choice == 'minute'){
        $tmp = $diff;
        $data[$choice] = floor($tmp/60);
    }
 
    if($data[$choice] > $duration) return true; 
    return false;
}

/**
 * Permet de générer un nouveau mot de passe (recover mod)
 */
function randPass($length = 8)
{
    $chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}