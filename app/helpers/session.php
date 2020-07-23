<?php
session_start();

/**
 * Permet d'insérer un petit message d'info à l'utilisateur suite à une action et le faire disparaitre au prochain refresh 
 * @param string $name
 * @param string $message
 * @param string $class
 * @return void
 */
function flash(string $name = '', string $message = '', string $class = 'alert alert-success alert-dismissible'): void
{
    if(!empty($name))
    {
        if(!empty($message) && empty($_SESSION[$name]))
        {
            if(!empty($_SESSION[$name])) { unset($_SESSION[$name]); }
            if(!empty($_SESSION[$name . '_class'])) { unset($_SESSION[$name . '_class']); }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        }
        elseif(empty($message) && !empty($_SESSION[$name]))
        {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" role="alert">';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            echo $_SESSION[$name];
            echo '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
 * Détecter si l'utilisateur est connecté ou non
 * @return bool
 */
function isLoggedIn(): bool
{    
    if(isset($_SESSION['user_id']) && !empty(['user_id'])) return true;
    return false;
}