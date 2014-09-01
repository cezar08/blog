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
class User extends Entity {

    protected $schemaName = 'schema_blog';

    /**
     * @var string
     */
    protected $tableName = 'user';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $role;

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
                        'name' => 'login',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToLower',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => '1',
                                    'max' => '100',
                                ),
                            ),
                        ),
                    ))
            );

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'password',
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
                        'name' => 'role',
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
                                    'max' => '100',
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
