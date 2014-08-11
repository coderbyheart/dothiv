<?php

namespace Dothiv\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validate the email address of an administrator.
 */
class ValidAdminEmailValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    private $adminUserDomain;

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (preg_match('/' . preg_quote($this->adminUserDomain) . '$/', $value) !== 1) {
            $this->context->addViolation($constraint->message, array('{{ value }}' => $value));
        }
    }

    /**
     * @param string $adminUserDomain
     */
    public function setAdminUserDomain($adminUserDomain)
    {
        $this->adminUserDomain = $adminUserDomain;
    }
}
