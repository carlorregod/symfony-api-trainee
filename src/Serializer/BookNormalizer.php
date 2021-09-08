<?php 

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookNormalizer implements ContextAwareNormalizerInterface
{

    private ObjectNormalizer $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlHelper $urlHelper
    ) {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($book, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);
        //Acá se pueden pasar mensajes personalizados
        if (!empty($book->getImagen())) {
            # Se obtensdrá la url absoluta de esta forma
            $data['imagen'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $book->getImagen());
        }
        $data['mensaje'] = 'hola';

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Book;
    }
}