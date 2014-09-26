<?php
namespace Admin\Controller;

use Core\Test\ControllerTestCase;
use Admin\Controller\PostsController;
use Admin\Model\Post;
use Admin\Model\PostCategorie;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */

class PostsControllerTest extends ControllerTestCase{
    
    /**
     *
     * @var string
     */
    protected $controllerFQDN = 'Admin\Controller\PostsController';
    
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
        $post1 = $this->addPost('Começando com ZF');
        $post2 = $this->addPost('ACLs com zf2');        
        $this->routeMatch->setParam('action','index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('posts', $variables);
        $controllerData = $variables['posts'];        
        $this->assertEquals($post2->title, $controllerData[0]['title']);
        $this->assertEquals(1, count($controllerData));
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
        $post1 = $this->addPost('Começando com zf2');
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $post1->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $id = $form->get('id');
        $title = $form->get('title');
        $description = $form->get('description');
        $text = $form->get('text');
        $categories = $form->get('categories');
        $this->assertEquals($post1->id, $id->getValue());
        $this->assertEquals($post1->title, $title->getValue());
        $this->assertEquals($post1->description, $description->getValue());
        $this->assertEquals($post1->text, $text->getValue());
        $this->assertEquals('multi_checkbox', $categories->getAttribute('type'));
    }
    
    public function testSaveActionPostRequest(){
        $categorie = $this->addCategorie();
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'Começando com zf2');
        $this->request->getPost()->set('description', 'Para começar este post...');
        $this->request->getPost()->set('text', 'Pensando mais a longo prazo, o entendimento das metas propostas ainda não demonstrou convincentemente que vai participar na mudança das formas de ação.');
        $this->request->getPost()->set('categories', array($categorie->id));
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $posts = $this->getTable('Admin\Model\Post')->fetchAll()->toArray();
        $this->assertEquals(1, count($posts));       
        $this->assertEquals('Começando com zf2', $posts[0]['title']);                
        $this->assertEquals('Para começar este post...', $posts[0]['description']);
        $this->assertEquals('Pensando mais a longo prazo, o entendimento das metas propostas ainda não demonstrou convincentemente que vai participar na mudança das formas de ação.', $posts[0]['text']);
    }
    
    public function testSaveActionInvalidPostRequest(){
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', '');
        $result = $this->controller->dispatch($this->request, $this->response);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $title = $form->get('title');
        $titleErrors = $title->getMessages();
        $this->assertEquals("Value is required and can't be empty", $titleErrors['isEmpty']);
        $description = $form->get('description');
        $descriptionErrors = $description->getMessages();
        $this->assertEquals("Value is required and can't be empty", $descriptionErrors['isEmpty']);
        $text = $form->get('text');
        $textErrors = $text->getMessages();
        $this->assertEquals("Value is required and can't be empty", $textErrors['isEmpty']);

        $categories = $form->get('categories');
        $categoriesErrors = $categories->getMessages();
        $this->assertEquals("Value is required and can't be empty", $categoriesErrors['isEmpty']);        
    }
    
    /**
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Código obrigatório
     */
    
    public function testInvalidDeleteAction(){
        $post = $this->addPost('Começando com zf2');
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
    }
    
    public function testDeleteAction(){
        $post = $this->addPost('Começando com zf2');
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $post->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $posts = $this->getTable('Admin\Model\Post')->fetchAll()->toArray();
        $this->assertEquals(0, count($posts));
    }
        

    private function addPost($title){
        $post = new \Admin\Model\Post();
        $post->id_user = $this->addUser();
        $post->title = $title;
        $post->description = 'Comece com um café';      
        $post->text = 'Todavia, o comprometimento entre as equipes acarreta um 
        processo de reformulação e modernização do sistema de formação de quadros 
        que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
        da Internet acarreta um processo 
        de reformulação e modernização das novas proposições.';
        $post->date_post = date('Y-m-d H:i:s');     
        $saved = $this->getTable('Admin\Model\Post')->save($post);
        return $post;
    }


    private function addCategorie(){
        $categorie = new \Admin\Model\Categorie();
        $categorie->description = 'Zend Framework';     
        $saved = $this->getTable('Admin\Model\Categorie')->save($categorie);
        return $categorie;
    }

    private function addUser(){
        $user = new \Admin\Model\User();
        $user->login = 'zoro';
        $user->password = md5('123456');
        $user->role = 'admin';
        $saved = $this->getTable('Admin\Model\User')->save($user);
        $session = $this->serviceManager->get('Session');
        $session->offsetSet('user', $user);
        return $saved->id;
    }
        
}

