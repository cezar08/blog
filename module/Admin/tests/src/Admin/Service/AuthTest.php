<?php

namespace Admin\Service;

use Core\Test\ServiceTestCase;
use Admin\Model\User;
use Zend\Authentication\AuthenticationService;

/**
 * @group Service
 */
class AuthTest extends ServiceTestCase {

    /**
     * @expectedException \Exception
     */
    public function testAuthenticateWithoutParams() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate();
    }

    /**
     * @expectedException \Exception
     * @expectedExeptionMessage Parâmetros inválidos
     */
    public function testAuthenticateEmptyParams() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos     
     */
    public function testAuthenticateInvalidParameters() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array(
            'login' => 'foo', 'password' => 'foo')
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos
     * 
     */
    public function testAuthenticateInvalidPassword() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $authService->authenticate(array(
            'login' => $user->login, 'password' => 'foo')
        );
    }

    public function testAuthenticateValidParams() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $result = $authService->authenticate(
                array('login' => $user->login, 'password' => '123456')
        );
        $this->assertTrue($result);
        $auth = new AuthenticationService();
        $this->assertEquals($auth->getIdentity(), $user->login);
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertEquals($user->id, $savedUser->id);
    }

    public function tearDown() {
        parent::tearDown();
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    }

    public function testLogout() {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $result = $authService->authenticate(
                array('login' => $user->login, 'password' => '123456')
        );
        $this->assertTrue($result);
        $result = $authService->logout();
        $this->assertTrue($result);
        $auth = new AuthenticationService();
        $this->assertNull($auth->getIdentity());
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertNull($savedUser);
    }

    /**
     * 
     * @return boolean
     */
    public function authorize() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            return true;
        }
        return false;
    }

    private function addUser() {
        $user = new User();
        $user->login = 'zoro';
        $user->password = md5('123456');
        $user->role = 'admin';
        $this->getTable('Admin\Model\User')->save($user);
        return $user;
    }

}
