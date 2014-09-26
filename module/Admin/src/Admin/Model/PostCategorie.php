<?php

namespace Admin\Model;

use Core\Model\Entity;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * @category Admin
 * @package Model
 *
 */
class PostCategorie extends Entity {

    protected $primaryKeyField = 'id_post';

    protected $schemaName = 'schema_blog';

    /**
     * @var string
     */
    protected $tableName = 'post_categories';

    /**
     * @var int
     */
    protected $id_post;

    /**
     * @var int
     */
    protected $id_categorie;

    /**
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'id_post',
                        'required' => true,                                            
                        'validators' => array(
                            array('name' => 'Digits')
                        ),
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'id_categorie',
                        'required' => true,                     
                        'validators' => array(
                            array('name' => 'Digits')
                        ),
                    ))
            );
                    
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
        
    }

}
