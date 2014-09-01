<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Admin\Form\Login;

/**
 * Controlador que gerencia os posts
 *
 * @category Admin
 * @package Controller
 * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>
 */
class AuthController extends ActionController {

    public function indexAction() {
        $form = new Login();
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function loginAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new \Exception('Acesso inválido');
        }

        $data = $request->getPost();
        $service = $this->getService('Admin\Service\Auth');
        $service->authenticate(
                array('login' => $data['login'], 'password' => $data['password'])
        );
        return $this->redirect()->toUrl('/');
    }

    public function logoutAction() {
        $service = $this->getService('Admin\Service\Auth');
        $service->logout();
        return $this->redirect()->toUrl('/');
    }

}
