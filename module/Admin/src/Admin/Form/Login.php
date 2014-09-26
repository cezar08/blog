<?php

namespace Admin\Form;

use Zend\Form\Form;

class Login extends Form {

    public function __construct() {
        parent::__construct('login');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/admin/auth/login');

        $this->add(array(
            'type' => 'text',
            'name' => 'login',
            'options' => array(
                'label' => 'Usuário:'
            ),
            'attributes' => array(
                'placeholder' => 'Entre com o seu usuário'
            ),
                )
        );

        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'options' => array(
                'label' => 'Usuário:'
            ),
            'attributes' => array(
                'placeholder' => 'Entre com sua senha'
            ),
                )
        );

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Entrar'
            ),
                )
        );
    }

}
