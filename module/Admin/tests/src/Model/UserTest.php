<?php
namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\User;
use Zend\InputFilter\InputFilterInterface;

/**
*@group Model
*/
class UserTest extends ModelTestCase{

	public function testGetInputFilter(){
		$user = new User();
		$filters = $user->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $filters);
		return $filters;
	}

	/**
	*@depends testGetInputFilter
	*/
	public function testInputFilterValid($filters){
		$this->assertEquals(4, $filters->count());

		$this->assertTrue($filters->has('id'));
		$this->assertTrue($filters->has('login'));
		$this->assertTrue($filters->has('password'));
		$this->assertTrue($filters->has('role'));
	}

	/**
	*@expectedException Core\Model\EntityException
	*/

	public function testInputFilterInvalid(){
		$user = new User();
		$user->login = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';		
	}

	public function testInsert(){
		$user = $this->addUser();
		$saved = $this->getTable('Admin\Model\User')->save($user);
		$this->assertEquals('zoro', $saved->login);
		$this->assertEquals(1, $saved->id);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedException Entrada inválida: password =
	*/
	public function testInsertInvalid(){
		$user = new User();
		$user->login = 'zoro';
		$user->password = '';
		$user->role = 'admin';
		$saved = $this->getTable('Admin\Model\User')->save($user);
	}

	public function testUpdate(){
		$tableGateway = $this->getTable('Admin\Model\User');
		$user = $this->addUser();
		$saved = $tableGateway->save($user);
		$id = $user->id;
		$this->assertEquals(1, $id);
		$user = $tableGateway->get($id);
		$this->assertEquals('zoro', $user->login);
		$user->login = 'Zoro_3';
		$updated = $tableGateway->save($user);
		$user = $tableGateway->get($id);
		$this->assertEquals('zoro_3', $user->login);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedExceptionMessage Não existem dados com o identificador 1
	*/
	public function testDelete(){
		$tableGateway = $this->getTable('Admin\Model\User');
		$user = $this->addUser();
		$saved = $tableGateway->save($user);
		$id = $saved->id;
		$deleted = $tableGateway->delete($id);
		$this->assertEquals(1, $deleted);
		$user = $tableGateway->get($id);
	}

	private function addUser(){
		$user = new User();
		$user->login = 'zoro';
		$user->password = md5('123456');
		$user->role = 'admin';
		return $user;
	}

}