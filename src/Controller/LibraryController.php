<?php 

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    
    /**
     * @Route("/book/list",name="book-list")
     */
    public function list(Request $request, BookRepository $bookrepo, LoggerInterface $logger)
    {
        // $logger->info('Codigo de prueba');
        $books = $bookrepo->findAll();
        $booksArr = array();
        foreach ($books as $clave => $value) {
            $booksArr[] = [
                'id'=>$value->getId(),
                'titulo'=>$value->getTitle(),
                'imagen'=>$value->getImagen(),
            ];
        }
        return new JsonResponse([
            'success'=>true,
            'data'=>$booksArr
        ],200);
    }


    /**
     * @Route("/book",name="create-book")
     */
    public function createBook(Request $request, EntityManagerInterface $em)
    {
        $book = new Book();
        $title = $request->get('title',null);

        if (empty($title)) {
            return new JsonResponse([
                'success'=>false,
                'data'=> null,
                'error'=>'No hay título'
            ],404);
        }

        $book->setTitle($title);
        $book->setImagen($request->get('imagen',''));
        $em->persist($book);
        $em->flush();

        return new JsonResponse([
            'success'=>true,
            'data'=> [
                'id'=>$book->getId(),
                'titulo'=>$book->getTitle(),
                'imagen'=>$book->getImagen()
            ]
        ],200);
    }
}