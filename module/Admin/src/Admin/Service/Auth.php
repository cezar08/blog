<?php

namespace Admin\Service;

use Core\Service\Service;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

/**
 * @category Admin
 * @package Service
 */
class Auth extends Service {

    /**
     * 
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    public function __construct($dbAdapter = null) {
        $this->dbAdapter = $dbAdapter;
        $user = new \Admin\Model\User();
        $this->dbAdapter->query("SET search_path TO $user->schemaName;", 'execute');
    }

    /**
     * 
     *
     * @param array $params
     * @return array
     */
    public function authenticate($params) {
        if (!isset($params['login']) || $params['login'] == '' || !isset($params['password']) || $params['password'] == '') {
            throw new \Exception("Parâmetros inválidos");
        }
        $password = md5($params['password']);
        $auth = new AuthenticationService();
        $authAdapter = new AuthAdapter($this->dbAdapter);
        $authAdapter
                ->setTableName('user')
                ->setIdentityColumn('login')
                ->setCredentialColumn('password')
                ->setIdentity($params['login'])
                ->setCredential($password);
        $result = $auth->authenticate($authAdapter);
        if (!$result->isValid()) {
            throw new \Exception("Login ou senha inválidos");
        }
        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $authAdapter->getResultRowObject());
        return true;
    }

    /**
     *
     * @param string $moduleName Nome do módulo sendo acessado
     * @param string $controllerName Nome do controller
     * @param string $actionName Nome da ação
     * @return boolean
     */
    public function authorize($moduleName, $controllerName, $actionName) {
        $auth = new AuthenticationService();
        $role = 'visitante';
        if ($auth->hasIdentity()) {
            $session = $this->getServiceManager()->get('Session');
            $user = $session->offsetGet('user');
            $role = $user->role;
        }
        $resource = $controllerName . '.' . $actionName;
        $acl = $this->getServiceManager()->get('Core\Acl\Builder')->build();
        if ($acl->isAllowed($role, $resource)) {
            return true;
        }
        return false;
    }

    /**
     * Faz o logout do sistema
     *
     * @return void
     */
    public function logout() {
        $auth = new AuthenticationService();
        $session = $this->getServiceManager()->get('Session');
        $session->offsetUnset('user');
        $auth->clearIdentity();
        return true;
    }

}
