<?php

namespace Admin\Form;

use Zend\Form\Form;

class User extends Form {

    public function __construct() {
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'login',
            'options' => array(
                'label' => 'Login*:',
            ),
            'attributes' => array(
                'placeholder' => 'Ex: pequeno_bob'
            ),
        ));

        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'options' => array(
                'label' => 'Senha*:',
            ),
            'attributes' => array(
                'placeholder' => 'Ex: Xter%45q'
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'role',
            'options' => array(
                'label' => 'Role*:',
                'empty_option' => 'Selecione um perfil',
                'value_options' => array('admin' => 'admin', 'editor' => 'editor')
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
