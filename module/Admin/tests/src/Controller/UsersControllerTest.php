<?php
namespace Admin\Controller;

use Core\Test\ControllerTestCase;
use Admin\Controller\UsersController;
use Admin\Model\User;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */

class UsersControllerTest extends ControllerTestCase{
    
    /**
     *
     * @var string
     */
    protected $controllerFQDN = 'Admin\Controller\UsersController';
    
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
        $user1 = $this->addUser('login1');
        $user2 = $this->addUser('login2');
        
        $this->routeMatch->setParam('action','index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('users', $variables);
        $controllerData = $variables['users'];
        $this->assertEquals($user1->login, $controllerData[0]['login']);
        $this->assertEquals($user2->login, $controllerData[1]['login']);
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
        $user1 = $this->addUser('mario');
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $user1->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $id = $form->get('id');
        $login = $form->get('login');
        $password = $form->get('password');
        $role = $form->get('role');
        $this->assertEquals($user1->id, $id->getValue());
        $this->assertEquals($user1->login, $login->getValue());
        $this->assertEquals('', $password->getValue());
        $this->assertEquals($user1->role, $role->getValue());
    }
    
    public function testSaveActionPostRequest(){
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('login', 'luigui');
        $this->request->getPost()->set('password', '123456');
        $this->request->getPost()->set('role', 'admin');
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $users = $this->getTable('Admin\Model\User')->fetchAll()->toArray();
        $this->assertEquals(1, count($users));       
        $this->assertEquals('luigui', $users[0]['login']);        
        $this->assertNotNull($users[0]['password']);
        $this->assertEquals('admin', $users[0]['role']);
    }
    
    public function testSaveActionInvalidPostRequest(){
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('login', '');
        $result = $this->controller->dispatch($this->request, $this->response);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $login = $form->get('login');
        $loginErrors = $login->getMessages();
        $this->assertEquals("Value is required and can't be empty", $loginErrors['isEmpty']);
        $password = $form->get('password');
        $passwordErrors = $password->getMessages();
        $this->assertEquals("Value is required and can't be empty", $passwordErrors['isEmpty']);
        $role = $form->get('role');
        $roleErrors = $role->getMessages();
        $this->assertEquals("Value is required and can't be empty", $roleErrors['isEmpty']);        
    }
    
    /**
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Código obrigatório
     */
    
    public function testInvalidDeleteAction(){
        $user = $this->addUser('bily');
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
    }
    
    public function testDeleteAction(){
        $user = $this->addUser('bily');
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $user->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $users = $this->getTable('Admin\Model\User')->fetchAll()->toArray();
        $this->assertEquals(0, count($users));
    }
    
    
    
    public function addUser($login){
        $user = new User();
        $user->login = $login;
        $user->password = md5('123456');
        $user->role = 'admin';
        $saved = $this->getTable('Admin\Model\User')->save($user);
        return $saved;
    }
        
}

