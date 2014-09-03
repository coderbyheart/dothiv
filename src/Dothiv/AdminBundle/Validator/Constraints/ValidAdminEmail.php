<?php

namespace Dothiv\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Registers the ValidAdminEmail annotation.
 *
 * @Annotation
 * @codeCoverageIgnore
 */
class ValidAdminEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Not an admin domain: "{{ value }}"!';

    public function validatedBy()
    {
        return 'admin_email_validator';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
