<?php

namespace Dothiv\APIBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model for a user creation request
 */
class UserCreateRequest
{
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public $surname;

    /*
    * @var string
    * @Assert\NotNull
    * @Assert\NotBlank
    */
    public $name;
}
