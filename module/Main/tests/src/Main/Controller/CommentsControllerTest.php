<?php
namespace Main\Controller;

use Core\Test\ControllerTestCase;
use Main\Controller\CommentsController;
use Main\Model\Comment;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */

class CommentsControllerTest extends ControllerTestCase{
    
    /**
     *
     * @var string
     */
    protected $controllerFQDN = 'Main\Controller\CommentsController';
    
    /**
     * @var string
     */
    
    protected $controllerRoute = 'main';
    
    public function test404(){
        $this->routeMatch->setParam('action', 'action-nao-existente');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndexAction(){
        $post = $this->addPost('Meu post');
        $comment1 = $this->addComment('#1', $post->id);
        $comment2 = $this->addComment('#2', $post->id);            
        $this->routeMatch->setParam('action','index');
        $this->routeMatch->setParam('id',$post->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('comments', $variables);
        $controllerData = $variables['comments'];
        $this->assertEquals($comment1->description, $controllerData[0]['description']);
        $this->assertEquals($comment2->description, $controllerData[1]['description']);
        $this->assertEquals(2, count($controllerData));
    }
    
    public function testSaveNewRequest(){
        $post = $this->addPost('Meu post');
        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('id', $post->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $idPost = $form->get('id_post');
        $this->assertEquals('id_post', $idPost->getName());
        $this->assertEquals('hidden', $idPost->getAttribute('type'));
        $this->assertEquals($post->id, $idPost->getValue());
    }
       
    public function testSavePostRequest(){
        $post = $this->addPost('Meu post');
        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('id', $post->id);
        $this->request->setMethod('post');
        $this->request->getPost()->set('id_post', $post->id);
        $this->request->getPost()->set('description', 'First');
        $this->request->getPost()->set('email', 'first@gmail.com');
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $comments = $this->getTable('Main\Model\Comment')->fetchAll(null, "id_post = ".$post->id)->toArray();
        $this->assertEquals(1, count($comments));       
        $this->assertEquals('First', $comments[0]['description']);        
        $this->assertNotNull($comments[0]['email']);        
    }
    
    public function testSaveInvalidPostRequest(){
        $post = $this->addPost('Meu post');
        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('id', $post->id);
        $this->request->setMethod('post');
        $this->request->getPost()->set('description', '');
        $result = $this->controller->dispatch($this->request, $this->response);
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        $description = $form->get('description');
        $descriptionErrors = $description->getMessages();
        $this->assertEquals("Value is required and can't be empty", $descriptionErrors['isEmpty']);
        $email = $form->get('email');
        $emailErrors = $email->getMessages();
        $this->assertEquals("Value is required and can't be empty", $emailErrors['isEmpty']);
        $idPost = $form->get('id_post');
        $idPostErrors = $idPost->getMessages();
        $this->assertEquals("Value is required and can't be empty", $idPostErrors['isEmpty']);        
    }

    /**
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Identificador do post é necessário
     */
    
    public function testInvalidDeleteAction(){        
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
    }
    
   private function addComment($description, $post){
        $comment = new Comment();
        $comment->id_post = $post;
        $comment->description = $description;
        $comment->email = 'teste@gmail.com';
        $comment->date = date('Y-m-d H:i:s');
        $saved = $this->getTable('Main\Model\Comment')->save($comment);
        return $saved;
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

