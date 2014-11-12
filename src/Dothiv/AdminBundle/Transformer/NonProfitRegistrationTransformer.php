<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\NonProfitRegistrationModel;
use Dothiv\APIBundle\Transformer\AbstractTransformer;
use Dothiv\APIBundle\Transformer\EntityTransformerInterface;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class NonProfitRegistrationTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\UserTransformerTrait;

    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
    {
        /** @var NonProfitRegistration $entity */
        $model = new NonProfitRegistrationModel();
        $model->setDomain(new HivDomainValue($entity->getDomain()));
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('identifier' => $entity->getDomain()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        $model->setApproved(!$entity->getApproved() ? null : $entity->getApproved());
        $model->setRegistered(!$entity->getRegistered() ? null : $entity->getRegistered());
        if (!$listing) {
            $model->setUser($this->getUserTransformer()->transform($entity->getUser(), null, $listing));
            $model->setPersonFirstname($entity->getPersonFirstname());
            $model->setPersonSurname($entity->getPersonSurname());
            $model->setPersonEmail(new EmailValue($entity->getPersonEmail()));
            $model->setOrganization($entity->getOrganization());
            $model->setProof($this->getAttachmentTransformer()->transform($entity->getProof(), null, $listing));
            $model->setAbout($entity->getAbout());
            $model->setField($entity->getField());
            $model->setPostcode($entity->getPostcode());
            $model->setLocality($entity->getLocality());
            $model->setCountry($entity->getCountry());
            $model->setWebsite(new URLValue($entity->getWebsite()));
            $model->setForward($entity->getForward());
            $model->setPersonPhone($entity->getPersonPhone());
            $model->setPersonFax($entity->getPersonFax());
            $model->setOrgPhone($entity->getOrgPhone());
            $model->setOrgFax($entity->getOrgFax());
        }
        return $model;
    }
}
