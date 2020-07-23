<?php
/*
 *  BASE CONTROLLER
 *  Chargement des modèles et des vues
 */
 class Controller 
 {
    /**
     * Chargement du modèle
     * @param string $model
     */
    protected function model(string $model)
    {
        if(file_exists('../app/models/' . $model . '.php'))
        {
            require_once '../app/models/' . $model . '.php';
            return new $model(); 
        }
        else
        {
            throw new Exception("Le modèle '$model' n'existe pas !"); 
        }
    }

    /**
     * Chargement de la vue et des données
     * @param string $view
     * @param array $data
     */
    protected function view(string $view, array $data = [])
    {
        if(file_exists('../app/views/' . $view . '.php'))
        {
            require_once '../app/views/' . $view . '.php';
        }
        else
        {
            throw new Exception("La vue '$view' n'existe pas !"); 
        }
    }
 }