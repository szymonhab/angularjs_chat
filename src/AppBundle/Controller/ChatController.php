<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Message;

/**
* @Route("/chat")
*/
class ChatController extends Controller
{

  /**
   * @Route("/post_message", name="post_message")
   * @Method("POST")
   */
  public function postMessage(Request $request)
  {
    session_write_close();
    $postdata = $this->getRequest()->getContent();
    $request  = json_decode($postdata);
    $content  = $request->message;

    $em = $this->getDoctrine()->getManager();
    $message = new Message($content, 'test');
    $em->persist($message);
    $em->flush($message);
    
    return new JsonResponse(true);
  }
  
  /**
   * @Route("/get_message", name="get_messages")
   * @Method("POST")
   */
  public function getMessages()
  {
    session_write_close();
    $postdata = $this->getRequest()->getContent();
    $request  = json_decode($postdata);
    $lastId   = $request->last_id;
    
    for($i = 0; $i < 10; $i++) {
      $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findOlderThan($lastId);
      if(!empty($messages)) {
        return new JsonResponse($messages);
      } else {
        usleep(500000);
      }
    }
    
    return new JsonResponse(false);
  }
  
  /**
   * @Route("/", name="chat")
   * @Template()
   */
  public function indexAction()
  {
  
    return array();
  }
}
