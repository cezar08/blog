<?php
namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\Categorie;

/**
*@group Model
*/
class CategorieTest extends ModelTestCase{

	public function testGetInputFilter(){
		$categorie = new Categorie();
		$filters = $categorie->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $filters);
		return $filters;
	}

	/**
	*@depends testGetInputFilter
	*/
	public function testInputFilterValid($filters){
		$this->assertEquals(2, $filters->count());
		$this->assertTrue($filters->has('id'));
		$this->assertTrue($filters->has('description'));		
	}

	/**
	*@expectedException Core\Model\EntityException
	*/

	public function testInputFilterInvalid(){
		$categorie = new Categorie();
		$categorie->description = 'Todavia, o comprometimento entre as equipes acarreta um 
		processo de reformulação e modernização do sistema de formação de quadros 
		que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
		da Internet acarreta um processo 
		de reformulação e modernização das novas proposições.';		
	}

	public function testInsert(){
		$categorie = $this->addCategorie();
		$saved = $this->getTable('Admin\Model\Categorie')->save($categorie);
		$this->assertEquals('Zend Framework', $saved->description);
		$this->assertEquals(1, $saved->id);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedException Entrada inválida: description =
	*/
	public function testInsertInvalid(){
		$categorie = new Categorie();		
		$categorie->description = '';		
		$this->getTable('Admin\Model\Categorie')->save($categorie);
	}

	public function testUpdate(){
		$tableGateway = $this->getTable('Admin\Model\Categorie');
		$categorie = $this->addCategorie();
		$saved = $tableGateway->save($categorie);
		$id = $categorie->id;
		$this->assertEquals(1, $id);
		$categorie = $tableGateway->get($id);
		$this->assertEquals('Zend Framework', $categorie->description);
		$categorie->description = 'Ruby on Rails';
		$updated = $tableGateway->save($categorie);
		$categorie = $tableGateway->get($id);
		$this->assertEquals('Ruby on Rails', $categorie->description);
	}

	/**
	*@expectedException Core\Model\EntityException
	*@expectedExceptionMessage Não existem dados com o identificador 1
	*/
	public function testDelete(){
		$tableGateway = $this->getTable('Admin\Model\Categorie');
		$categorie = $this->addCategorie();
		$saved = $tableGateway->save($categorie);
		$id = $saved->id;
		$deleted = $tableGateway->delete($id);
		$this->assertEquals(1, $deleted);
		$tableGateway->get($id);
	}

	private function addCategorie(){
		$categorie = new Categorie();
		$categorie->description = 'Zend Framework';		
		return $categorie;
	}

}