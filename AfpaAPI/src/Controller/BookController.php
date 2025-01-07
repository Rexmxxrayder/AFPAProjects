<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
class BookController extends AbstractController
{
    #[Route('/book', name: 'get_books', methods: ['GET'])]
    public function getBooks(BookRepository $br): JsonResponse
    {
        $books = $br->findAll();
        return $this->json($books);
    }

    #[Route('/book/{id}', name: 'get_book', methods: ['GET'])]
    public function getBook(int $id, BookRepository $br): JsonResponse
    {
        $book = $br->find($id);
        if(!$book){
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($book);
    }

    #[Route('/book', name: 'create_book', methods: ['POST'])]
    public function createBook(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = $request->getContent();
        $book = $serializer->deserialize($jsonData, Book::class, 'json');
        $em->persist($book);
        $em->flush();

        return new JsonResponse(['status' => 'Book Created!'], 201);
    }

    #[Route('/book/{id}', name: 'modify_book', methods: ['PUT'])]
    public function bookModify(int $id, Request $request, BookRepository $br, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $book = $br->find($id);
        if(!$book){
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
        $serializer->deserialize($request->getContent(), Book::class, 'json', ['object_to_populate' => $book]);
        $em->flush();

        return $this->json($book);
    }

    #[Route('/book/{id}', name: 'book_delete', methods: ['DELETE'])]
    public function deleteItem(int $id, BookRepository $br, EntityManagerInterface $em): JsonResponse
    {
        $book = $br->find($id);
        if(!$book){
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
        $em->remove($book);
        $em->flush();
        return $this->json(['message' => 'Book deleted', Response::HTTP_NO_CONTENT]);
    }
}
