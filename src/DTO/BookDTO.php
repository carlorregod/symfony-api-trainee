<?php 

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class BookDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    public $title;

    
    public $base64Image;
}