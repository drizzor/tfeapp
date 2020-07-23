<?php

/**
 * Etablir un lien html
 */
function anchor(string $link, string $text, string $title, $extras, ?string $span = null)
{
    $data = '<a href="' . $link . '"';
     
    if($title)
    {
        $data .= ' title="' . $title . '"';
    }
    else
    {
        $data .= ' title="' . $text . '"';
    }    
     
    if(is_array($extras))
    {
        foreach($extras as $rule)
        {
            $data .= parse_extras($rule);
        }
    }
     
    if(is_string($extras))
    {
        $data .= parse_extras($extras);
    }
         
    $data.= '>';
    if($span != null) $data .= '<span class="' . $span . '"></span> ' . $text;
    else $data .= $text;    
    $data .= "</a>";     
    return $data;
}

/**
 * Controler la teneur de la variable $extras (id?, class?, target?)
 */
function parse_extras($rule) 
{
    if($rule[0] == "#") 
    {
        $id = substr($rule, 1, strlen($rule)); // Ici la combinaison du sbstr et strlen va permettre d'enlever le 1er caractère 
        $data = ' id="' . $id . '"';
        return $data;
    }
     
    if($rule[0] == ".")
    {
        $class = substr($rule,1,strlen($rule));
        $data = ' class="' . $class . '"';
        return $data;
    }
     
    if($rule[0] == "_") 
    {
        $data = ' target="_blank"';
        return $data;
    }
}