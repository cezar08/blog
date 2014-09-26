<?php
namespace Admin\Controller;

use Core\Test\ControllerTestCase;
use Admin\Controller\CategoriesController;
use Admin\Model\Categorie;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */

class CategoriesControllerTest extends ControllerTestCase{
    
    /**
     *
     * @var string
     */
    protected $controllerFQDN = 'Admin\Controller\CategoriesController';
    
    /**
     * @var string
     */
    
    protected $controllerRoute = 'admin';
    
    public function test404(){
        $this->routeMatch->setParam('action', 'action-nao-existente');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndexAction(){
        $categorie1 = $this->addCategorie('ZF2');
        $categorie2 = $this->addCategorie('Ruby');
        
        $this->routeMatch->setParam('action','index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('categories', $variables);
        $controllerData = $variables['categories'];
        $this->assertEquals($categorie1->description, $controllerData[1]['description']);
        $this->assertEquals($categorie2->description, $controllerData[0]['description']);
    }
    
    public function testSaveActionNewRequest(){
        $this->routeMatch->setParam('action', 'save');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $id = $form->get('id');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals('hidden', $id->getAttribute('type'));
    }
    
    public function testSaveActionUpdateFormRequest(){
        $categorie1 = $this->addCategorie('Web');
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $categorie1->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $id = $form->get('id');
        $description = $form->get('description');        
        $this->assertEquals($categorie1->id, $id->getValue());
        $this->assertEquals($categorie1->description, $description->getValue());        
    }
    
    public function testSaveActionPostRequest(){
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('description', 'Web');        
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $categories = $this->getTable('Admin\Model\Categorie')->fetchAll()->toArray();
        $this->assertEquals(1, count($categories));       
        $this->assertEquals('Web', $categories[0]['description']);                       
    }
    
    public function testSaveActionInvalidPostRequest(){
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('description', '');
        $result = $this->controller->dispatch($this->request, $this->response);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $description = $form->get('description');
        $descriptionErrors = $description->getMessages();
        $this->assertEquals("Value is required and can't be empty", $descriptionErrors['isEmpty']);        
    }
    
    /**
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Código obrigatório
     */
    
    public function testInvalidDeleteAction(){
        $this->addCategorie('Web');
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
    }
    
    public function testDeleteAction(){
        $categorie = $this->addCategorie('Web');
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $categorie->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $categories = $this->getTable('Admin\Model\Categorie')->fetchAll()->toArray();
        $this->assertEquals(0, count($categories));
    }
    
    
    
    public function addCategorie($description){
        $categorie = new Categorie();
        $categorie->description = $description;        
        $saved = $this->getTable('Admin\Model\Categorie')->save($categorie);
        return $saved;
    }
        
}

