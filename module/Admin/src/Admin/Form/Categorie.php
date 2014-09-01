<?php

namespace Admin\Form;

use Zend\Form\Form;

class Categorie extends Form {

    public function __construct() {
        parent::__construct('categorie');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'description',
            'options' => array(
                'label' => 'Descrição*:',
            ),
            'attributes' => array(
                'placeholder' => 'Ex: Desenvolvimento'
            ),
        ));
        
        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Salvar'
            ),
                )
        );
    }

}
