<?php

namespace Admin\Form;

use Zend\Form\Form;

class Post extends Form {

    public function __construct($categories) {
        parent::__construct('post');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'title',
            'options' => array(
                'label' => 'Titulo*:',
            ),
            'attributes' => array(
                'placeholder' => 'Ex: Entre com o titulo aqui'
            ),
        ));

        $this->add(array(
            'type' => 'textarea',
            'name' => 'description',
            'options' => array(
                'label' => 'Mini descrição*:',
            ),            
        ));

        $this->add(array(
            'type' => 'textarea',
            'name' => 'text', 
	    'options' => array(
                'label' => 'Descrição completa*:',
            ),            
        ));
	
        $this->add(array(
            'type' => 'MultiCheckbox',
            'name' => 'categories',
            'options' => array(
                'label' => 'Categorias*:',                                
                'value_options' => $categories,
                'disable_inarray_validator' => true,
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
