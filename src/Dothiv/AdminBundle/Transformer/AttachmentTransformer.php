<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\AttachmentModel;
use Dothiv\AdminBundle\Transformer\Traits\SecurityContextTrait;
use Dothiv\APIBundle\Security\Authentication\Token\Oauth2BearerToken;
use Dothiv\BusinessBundle\Entity\Attachment;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class AttachmentTransformer extends AbstractTransformer
{
    use SecurityContextTrait;

    /**
     * @param Attachment $entity
     * @param string     $route
     *
     * @return AttachmentModel
     */
    public function transform(Attachment $entity, $route = null)
    {
        $model = new AttachmentModel();
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('handle' => $entity->getHandle()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        $attachmentRoute = Option::fromValue($route)->getOrElse($this->route);
        $params = array('handle' => $entity->getHandle(), 'download' => '1');
        if (Option::fromValue($this->getSecurityContext())->isDefined()) {
            /** @var Oauth2BearerToken $token */
            $token = $this->getSecurityContext()->getToken();
            $params['auth_token'] = $token->getBearerToken();
        }
        $model->setUrl(new URLValue($this->router->generate(
            $attachmentRoute,
            $params,
            RouterInterface::ABSOLUTE_URL
        )));
        $model->setMimeType($entity->getMimeType());
        return $model;
    }
}
