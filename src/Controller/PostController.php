<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class PostController extends AbstractController
{

    private $normalizer;

public function __construct(NormalizerInterface $normalizer)
{
    $this->normalizer = $normalizer;
}
   #[Route('/api/all', name: 'all', methods: ['GET'])]
    public function all(EntityManagerInterface $entityManager): JsonResponse
{
    $posts = $entityManager->getRepository(Post::class)->findAll();
    $responseData = [];

    foreach ($posts as $post) {
        $responseData[] = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'category' => $post->getCategory() 
        ];
    }

    return $this->json($responseData);
}    
#[Route('/api/post', name: 'app_post', methods: ['POST'])]
public function postApi(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $post = new Post();
    $data = json_decode($request->getContent(), true);
    $post->setTitle($data['title']);
    $post->setContent($data['content']);
    $post->setCategory($data['category']);

    $entityManager->persist($post);
    $entityManager->flush();

    return $this->json($post);
}


    #[Route('/api/update/{id}', name: 'app_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $post = $entityManager->getRepository(Post::class)->find($id);
        
        if (!$post) {
            return $this->json('Post not found', 404);
        }
    
        $data = json_decode($request->getContent(), true);
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setCategory($data['category']);
    
        $entityManager->flush();
      

        return $this->json($post);
    }
    


#[Route('/api/delete/{id}', name: 'app_delete', methods: ['DELETE'])]
public function delete(EntityManagerInterface $entityManager, $id): JsonResponse
{
    $post = $entityManager->getRepository(Post::class)->find($id);
    
    if (!$post) {
        return $this->json('Post not found', 404);
    }

    $entityManager->remove($post);
    $entityManager->flush();

    return $this->json('Deleted successfully');
}
#[Route('/api/get/{id}', name: 'app_getpostbyid', methods: ['GET'])]
public function getpostbyid(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
{
    $post = $entityManager->getRepository(Post::class)->find($id);
    
    if (!$post) {
        return $this->json('Post not found', 404);
    }

    $responseData = [
        'id' => $post->getId(),
        'title' => $post->getTitle(),
        'content' => $post->getContent(),
        'category' => $post->getCategory(), 
    ];

    return $this->json($responseData);
}
}
