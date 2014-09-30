<?php

namespace Main\Form;

use Zend\Form\Form;

class Comment extends Form {

    public function __construct() {
        parent::__construct('comment');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', 'javascript:ajaxPost();');
        $this->setAttribute('id', 'form');

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id_post',
        ));

        $this->add(array(
            'type' => 'textarea',
            'name' => 'description',
            'options' => array(
                'label' => 'Comentario*:',
            ),            
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail*:',
            ),
            'attributes' => array(
                'placeholder' => 'Ex: email@host.com',
            )            
        ));
       
        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Comentar'
            ),
                )
        );
    }

}
