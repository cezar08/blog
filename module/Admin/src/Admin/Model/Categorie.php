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
class Categorie extends Entity {

    protected $schemaName = 'schema_blog';

    /**
     * @var string
     */
    protected $tableName = 'categorie';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;    

    /**
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'id',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int')
                        )
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'description',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),                            
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => '1',
                                    'max' => '255',
                                ),
                            ),
                        ),
                    ))
            );
        
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
        
    }

}
