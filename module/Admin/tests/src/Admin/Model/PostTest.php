<?php
namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\Post;

/**
*@group Model
*/
class PostTest extends ModelTestCase{

	public function testGetInputFilter(){
		$post = new Post();
		$filters = $post->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $filters);
		return $filters;
	}

	/**
	*@depends testGetInputFilter
	*/
	public function testInputFilterValid($filters){
		$this->assertEquals(6, $filters->count());
		$this->assertTrue($filters->has('id'));
		$this->assertTrue($filters->has('id_user'));		
		$this->assertTrue($filters->has('title'));
		$this->assertTrue($filters->has('description'));		
		$this->assertTrue($filters->has('text'));
		$this->assertTrue($filters->has('date_post'));		
	}

	/**
	*@expectedException Core\Model\EntityException
	*/

	public function testInputFilterInvalid(){
		$post = new Post();
		$post->title = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';		
	}

	public function testInsert(){
		$post = $this->addPost();
		$saved = $this->getTable('Admin\Model\Post')->save($post);
		$this->assertEquals('Criando um blog com Zend Framework', $saved->title);
		$this->assertEquals(1, $saved->id);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedException Entrada inválida: description =
	*/
	public function testInsertInvalid(){
		$post = new Post();		
		$post->title = '';		
		$this->getTable('Admin\Model\Post')->save($post);
	}

	public function testUpdate(){
		$tableGateway = $this->getTable('Admin\Model\Post');
		$post = $this->addPost();
		$saved = $tableGateway->save($post);
		$id = $post->id;
		$this->assertEquals(1, $id);
		$post = $tableGateway->get($id);
		$this->assertEquals('Criando um blog com Zend Framework', $post->title);
		$post->title = 'Hello world';
		$updated = $tableGateway->save($post);
		$post = $tableGateway->get($id);
		$this->assertEquals('Hello world', $post->title);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedExceptionMessage Não existem dados com o identificador 1
	*/
	public function testDelete(){
		$tableGateway = $this->getTable('Admin\Model\Post');
		$post = $this->addPost();
		$saved = $tableGateway->save($post);
		$id = $saved->id;
		$deleted = $tableGateway->delete($id);
		$this->assertEquals(1, $deleted);
		$tableGateway->get($id);
	}

	private function addPost(){
		$post = new Post();
		$post->id_user = $this->addUser();
		$post->title = 'Criando um blog com Zend Framework';
		$post->description = 'Comece com um café';		
		$post->text = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';
		$post->date_post = date('Y-m-d H:i:s');		
		return $post;
	}
	private function addUser(){
		$user = new User();
		$user->login = 'zoro';
		$user->password = md5('123456');
		$user->role = 'admin';
		$saved = $this->getTable('Admin\Model\User')->save($user);
		return $saved->id;
	}

}