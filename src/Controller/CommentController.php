<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;


class CommentController extends AbstractController
{
    #[Route('/comment/all', name: 'comment_allC', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        $responseData = [];

        foreach ($comments as $comment) {
            $responseData[] = [
                'id' => $comment->getId(),
                'author' => $comment->getAuthor(),
                'content' => $comment->getContent(),
                'post_id' => $comment->getPost() ? $comment->getPost()->getId() : null,
            ];
        }

        return new JsonResponse($responseData);
    }

    #[Route('/comment/post', name: 'comment_post', methods: ['POST'])]
    public function postComment(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
{
    $comment = new Comment();
    $data = json_decode($request->getContent(), true);
    $comment->setAuthor($data['author']);
    $comment->setContent($data['content']);
    $post = $entityManager->getRepository(Post::class)->find($data['post_id']);

    if (!$post) {
        return new JsonResponse(['error' => 'Post not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    $comment->setPost($post);

    $entityManager->persist($comment);
    $entityManager->flush();

    $commentData = $serializer->serialize($comment, 'json', ['groups' => 'comment:read']);

    return $this->json($commentData);
}

#[Route('/comment/update/{id}', name: 'comment_update', methods: ['PUT'])]
public function updateComment(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
{
    $comment = $entityManager->getRepository(Comment::class)->find($id);
    
    if (!$comment) {
        return new JsonResponse('Comment not found', JsonResponse::HTTP_NOT_FOUND);
    }

    $data = json_decode($request->getContent(), true);
    $comment->setAuthor($data['author']);
    $comment->setContent($data['content']);
    
    $entityManager->flush();
    
    $responseData = [
        'id' => $comment->getId(),
        'author' => $comment->getAuthor(),
        'content' => $comment->getContent(),
        'post_id' => $comment->getPost() ? $comment->getPost()->getId() : null,
    ];

    return new JsonResponse($responseData);
}




#[Route('/comment/delete/{id}', name: 'comment_delete', methods: ['DELETE'])]
public function deleteComment(EntityManagerInterface $entityManager, $id): JsonResponse
{
    $comment = $entityManager->getRepository(Comment::class)->find($id);
    
    if (!$comment) {
        return $this->json('comment not found', 404);
    }

    $entityManager->remove($comment);
    $entityManager->flush();

    return $this->json('Deleted successfully');
}
#[Route('/comment/get/{id}', name: 'comment_by_post', methods: ['GET'])]
public function getCommentsByPost($id, EntityManagerInterface $entityManager): JsonResponse
{
    $comments = $entityManager->getRepository(Comment::class)->findBy(['post' => $id]);
    $responseData = [];

    foreach ($comments as $comment) {
        $responseData[] = [
            'id' => $comment->getId(),
            'author' => $comment->getAuthor(),
            'content' => $comment->getContent(),
            'post_id' => $comment->getPost() ? $comment->getPost()->getId() : null,
        ];
    }

    return new JsonResponse($responseData);
}

}
