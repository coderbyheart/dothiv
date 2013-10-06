<?php

namespace DotHiv\BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            //->add('owner', 'entity', array('read_only' => true))
            ->add('emailAddressFromRegistrar')
            ->add('dnsForward')
            ->add('claimingToken', 'text', array('read_only' => true))
            ->add('id', 'text', array('read_only' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DotHiv\BusinessBundle\Entity\Domain',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return '';
    }
}
