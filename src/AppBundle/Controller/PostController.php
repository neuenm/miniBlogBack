<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class PostController extends Controller
{
    /**
     * @Route("posts/{id}", name="show_post")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        $post=$this->getDoctrine()->getRepository("AppBundle:Post")->find($id);
        if (empty($post)){
            $response=array(
                'code'=>1,
                'message'=>'No hay posts',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response,400);
        }
        $data=$this->get('jms_serializer')->serialize($post,'json');
        $response=array(
            'code'=>0,
            'message'=>'succes',
            'errors'=>'null',
            'result'=>json_decode($data)
        );
        return new JsonResponse($response, 200);
    }




    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("admin/posts/create", name="create_post")
     * @Method ({"POST"})
     */
    public function createAction(Request $request)
    {
        $data=$request->getContent();
        $post=$this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\Post', 'json');
        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Post creado!',
            'errors'=>null,
            'result'=>null,
        );
        return new JsonResponse($response,200);
    }




    /**
     * @Route("/posts"), name="list_posts"
     * @METHOD({"GET"})
     *
     */
    public function listAction()
    {
        $post=$this->getDoctrine()->getRepository("AppBundle:Post")->findAll();
        if (!count($post)){
            $response=array(
                'code'=>1,
                'message'=>'No hay posts',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response,200);
        }
        $data=$this->get('jms_serializer')->serialize($post,'json');
        $response=array(
            'code'=>0,
            'message'=>'succes',
            'errors'=>'null',
            'result'=>json_decode($data)
        );
        return new JsonResponse($response, 200);
    }

    /**
     * @param $id
     * @Route("/update/{id}", name="update_post")
     * @METHOD({"PUT"})
     * @return JsonResponse
     */
    public function updateAction(Request $request ,$id)
    {
        $post=$this->getDoctrine()->getRepository("AppBundle:Post")->find($id);
        if (empty($post)){
            $response=array(
                'code'=>1,
                'message'=>'No hay posts',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response,400);
        }

        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'AppBundle\Entity\Post','json');
        $post->setTitle($data->getTitle());
        $post->setDescription($data->getDescription());
        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>"post actualizado",
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     * @Route("/delete/{id}")
     * @METHOD({"DELETE"})
     */
    public function deleteAction($id)
    {
       $post=$this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
       if (empty($post)){
           $response=array(
               'code'=>1,
               'message'=>'Ńo exixte este Post',
               'errors'=>'null',
               'result'=>'null'
           );
           return new JsonResponse($response, 400);
       }
       $em=$this->getDoctrine()->getManager();
       $em->remove($post);
       $em->flush();
       $response=array(
           'code'=>0,
           'message'=>'El post fue eliminado',
           'errors'=>'null',
           'result'=>'null'
       );
       return new JsonResponse($response, 200);
    }
}
