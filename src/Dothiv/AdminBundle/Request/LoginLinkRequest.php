<?php

namespace Dothiv\AdminBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Dothiv\AdminBundle\Validator\Constraints\ValidAdminEmail;

/**
 * Model for a login link request
 */
class LoginLinkRequest extends \Dothiv\APIBundle\Request\LoginLinkRequest
{
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Email
     * @ValidAdminEmail
     */
    public $email;
}
