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
class Post extends Entity {

    protected $schemaName = 'schema_blog';

    /**
     * @var string
     */
    protected $tableName = 'post';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $id_user;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var date
     */
    protected $date_post;

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
                        'name' => 'id_user',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int')
                        )
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'title',
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

           $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'description',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),    
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => '1',
                                    'max' => '400',
                                ),
                            ),
                        ),
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'text',
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
                        'name' => 'date_post',
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
