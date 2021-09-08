<?php 

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\DTO\BookDTO;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use League\Flysystem\FilesystemOperator;
use App\Service\FileUploader;

class BookController extends AbstractFOSRestController
{
    #api/books 
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        BookRepository $bookRepository
    ) {
        return $bookRepository->findAll();
    }



    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(Request $request, FileUploader $fileuploader, EntityManagerInterface $em) 
    {        
        $bookDTO = new BookDTO();
        $form = $this->createForm(BookFormType::class, $bookDTO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // tratamiento de la imagen
            $filename = $fileuploader->uploadBase64File($bookDTO->base64Image);

            $book = new Book();
            $book->setTitle($bookDTO->title);
            $book->setImagen($filename);
            $em->persist($book);
            $em->flush();
            return $book;
        }

        return $form;
        
    }
}