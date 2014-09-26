<?php
namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\PostCategorie;

/**
*@group Model
*/
class PostCategorieTest extends ModelTestCase{

	public function testGetInputFilter(){
		$postCategorie = new PostCategorie();
		$filters = $postCategorie->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $filters);
		return $filters;
	}

	/**
	*@depends testGetInputFilter
	*/
	public function testInputFilterValid($filters){
		$this->assertEquals(2, $filters->count());
		$this->assertTrue($filters->has('id_post'));
		$this->assertTrue($filters->has('id_categorie'));				
	}

	/**
	*@expectedException Core\Model\EntityException
	*/

	public function testInputFilterInvalid(){
		$postCategorie = new PostCategorie();
		$postCategorie->id_post = 'Que';		
	}

	public function testInsert(){
		$postCategorie = $this->addPostCategorie();
		$saved = $this->getTable('Admin\Model\PostCategorie')->save($postCategorie, true);
		$this->assertEquals(1, $saved->id_post);
		$this->assertEquals(1, $saved->id_categorie);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedException Entrada inválida: description =
	*/
	
	public function testInsertInvalid(){
		$postCategorie = new PostCategorie();		
		$postCategorie->id_post = '';		
		$this->getTable('Admin\Model\PostCategorie')->save($postCategorie, true);
	}

	/**
	*@expectedException Zend\Db\Adapter\Exception\InvalidQueryException
	*
	*/
	
	public function testDuplicateInsertInvalid(){
		$postCategorie = new PostCategorie();		
		$postCategorie = $this->addPostCategorie();
		$saved = $this->getTable('Admin\Model\PostCategorie')->save($postCategorie, true);	
		$saved = $this->getTable('Admin\Model\PostCategorie')->save($postCategorie, true);
	}


	/**
	*@expectedException Core\Model\EntityException
	*@expectedExceptionMessage Não existem dados com o identificador 1
	*/
	public function testDelete(){
		$tableGateway = $this->getTable('Admin\Model\PostCategorie');
		$postCategorie = $this->addPostCategorie();
		$saved = $tableGateway->save($postCategorie);
		$id = $saved->id_post;
		$deleted = $tableGateway->delete($id);
		$this->assertEquals(1, $deleted);
		$tableGateway->get($id);
	}

	private function addPostCategorie(){
		$postCategorie = new PostCategorie();
		$postCategorie->id_post = $this->addPost();
		$postCategorie->id_categorie = $this->addCategorie();
		return $postCategorie;
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
		$saved = $this->getTable('Admin\Model\Post')->save($post);
		return $post->id;
	}


	private function addCategorie(){
		$categorie = new \Admin\Model\Categorie();
		$categorie->description = 'Zend Framework';		
		$saved = $this->getTable('Admin\Model\Categorie')->save($categorie);
		return $categorie->id;
	}

	private function addUser(){
		$user = new \Admin\Model\User();
		$user->login = 'zoro';
		$user->password = md5('123456');
		$user->role = 'admin';
		$saved = $this->getTable('Admin\Model\User')->save($user);
		return $saved->id;
	}

}