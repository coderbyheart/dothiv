<?php

namespace DotHiv\BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type used for user registration.
 *
 * @author Nils Wisiol <mail@nils-wisiol.de>
 *
 */
class UserRegisterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username');
        $builder->add('email');
        $builder->add('plainPassword');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'DotHiv\BusinessBundle\Entity\User',
                'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return '';
    }

}