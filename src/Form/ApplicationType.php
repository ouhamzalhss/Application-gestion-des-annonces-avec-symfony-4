<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{
   
     /**
     * Permet d'avoir la configuration de base d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param string $options
     * @return array
     */
    protected function getConfig($label,$placeholder,$options = []){
        return array_merge_recursive([
            'label'=> $label,
            'attr'=>[
                'placeholder'=> $placeholder
            ]],$options);
    }
}

