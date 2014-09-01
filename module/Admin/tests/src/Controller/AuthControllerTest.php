<?php

use Core\Test\ControllerTestCase;
use Admin\Model\User;

/**
 * @group Controller
 */
class AuthControllerTest extends ControllerTestCase {

    /**
     * 
     * @var string
     */
    protected $controllerFQDN = 'Admin\Controller\AuthController';

    /**
     * 
     * @var string
     */
    protected $controllerRoute = 'admin';

    public function test404() {
        $this->routeMatch->setParam('action', 'action-nao-existente');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testIndexActionLoginForm() {
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('form', $variables);
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $username = $form->get('login');
        $this->assertEquals('login', $username->getName());
        $this->assertEquals('text', $username->getAttribute('type'));
        $password = $form->get('password');
        $this->assertEquals('password', $password->getName());
        $this->assertEquals('password', $password->getAttribute('type'));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Acesso inválido
     */
    public function testLoginInvalidMethod() {
        $this->addUser();
        $this->routeMatch->setParam('action', 'login');
        $this->controller->dispatch($this->request, $this->response);
    }

    public function testLogin() {
        $user = $this->addUser();
        $this->request->setMethod('post');
        $this->request->getPost()->set('login', $user->login);
        $this->request->getPost()->set('password', '123456');
        $this->routeMatch->setParam('action', 'login');
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /', $headers->get('Location'));
    }

    public function testLogout() {
        $this->addUser();
        $this->routeMatch->setParam('action', 'logout');
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /', $headers->get('Location'));
    }

    private function addUser() {
        $user = new User();
        $user->login = 'luigui';
        $user->password = md5('123456');                
        $user->role = 'admin';
        $saved = $this->getTable('Admin\Model\User')->save($user);
        return $saved;
    }

}
