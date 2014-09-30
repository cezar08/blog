<?php
namespace Main\Model;

use Core\Test\ModelTestCase;
use Main\Model\Comment;

/**
*@group Model
*/
class CommentTest extends ModelTestCase{

	public function testGetInputFilter(){
		$comment = new Comment();
		$filters = $comment->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $filters);
		return $filters;
	}

	/**
	*@depends testGetInputFilter
	*/
	public function testInputFilterValid($filters){
		$this->assertEquals(5, $filters->count());
		$this->assertTrue($filters->has('id'));
		$this->assertTrue($filters->has('id_post'));		
		$this->assertTrue($filters->has('description'));
		$this->assertTrue($filters->has('email'));		
		$this->assertTrue($filters->has('date'));		
	}

	/**
	*@expectedException Core\Model\EntityException
	*/

	public function testInputFilterInvalid(){
		$comment = new Comment();
		$comment->email = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';		
	}

	public function testInsert(){
		$comment = $this->addComment();
		$saved = $this->getTable('Main\Model\Comment')->save($comment);
		$this->assertEquals('First', $saved->description);
		$this->assertEquals(1, $saved->id);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedException Entrada inválida: description =
	*/
	public function testInsertInvalid(){
		$comment = new Comment();		
		$comment->description = '';		
		$this->getTable('Main\Model\Comment')->save($comment);
	}

	public function testUpdate(){
		$tableGateway = $this->getTable('Main\Model\Comment');
		$comment = $this->addComment();
		$saved = $tableGateway->save($comment);
		$id = $saved->id;
		$this->assertEquals(1, $id);
		$comment = $tableGateway->get($id);
		$this->assertEquals('First', $comment->description);
		$comment->description = '#1';
		$updated = $tableGateway->save($comment);
		$comment = $tableGateway->get($id);
		$this->assertEquals('#1', $comment->description);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedExceptionMessage Não existem dados com o identificador 1
	*/
	public function testDelete(){
		$tableGateway = $this->getTable('\Main\Model\Comment');
		$comment = $this->addComment();
		$saved = $tableGateway->save($comment);
		$id = $saved->id;
		$deleted = $tableGateway->delete($id);
		$this->assertEquals(1, $deleted);
		$tableGateway->get($id);
	}

	private function addComment(){
		$comment = new Comment();
		$comment->id_post = $this->addPost();
		$comment->description='First';
		$comment->email = 'joaozinho@gmail.com';
		$comment->date = date('Y-m-d H:i:s');
		return $comment;
	}

	private function addPost(){
		$post = new \Admin\Model\Post();
		$post->id_user = $this->addUser();
		$post->title = 'Criando um blog com Zend Framework';
		$post->description = 'Comece com um café';		
		$post->text = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';
		$post->date_post = date('Y-m-d H:i:s');		
		$saved = $this->getTable('\Admin\Model\Post')->save($post);
		return $post->id;
	}
	private function addUser(){
		$user = new \Admin\Model\User();
		$user->login = 'zoro';
		$user->password = md5('123456');
		$user->role = 'admin';
		$saved = $this->getTable('\Admin\Model\User')->save($user);
		return $saved->id;
	}

}