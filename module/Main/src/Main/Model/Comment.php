<?php

namespace Main\Model;

use Core\Model\Entity;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * @category Main
 * @package Model
 *
 */
class Comment extends Entity {

    protected $schemaName = 'schema_blog';

    /**
     * @var string
     */
    protected $tableName = 'comment';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $id_post;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var datetime
     */
    protected $date;
    

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
                        'name' => 'id_post',
                        'required' => true,
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
                                    'max' => '4000',
                                ),
                            ),
                        ),
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'email',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),    
                        ),
                        'validators' => array(
                            array(
                                'name' => 'EmailAddress',                                
                            ),
                        ),
                    ))
            );
         
            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'date',
                        'required' => true,                        
                        'validators' => array(
                            array(
                                'name' => 'Date',
                                'options' => array(
                                    'format' => 'Y-m-d H:i:s'                                    
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
