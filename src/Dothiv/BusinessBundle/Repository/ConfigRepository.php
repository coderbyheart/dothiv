<?php


namespace Dothiv\BusinessBundle\Repository;

use Dothiv\BusinessBundle\Entity\Config;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Dothiv\BusinessBundle\Repository\Traits\ValidatorTrait;
use PhpOption\Option;

class ConfigRepository extends DoctrineEntityRepository implements ConfigRepositoryInterface
{
    use ValidatorTrait;

    /**
     * {@inheritdoc}
     */
    public function persist(Config $config)
    {
        $this->getEntityManager()->persist($this->validate($config));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    function get($key)
    {
        return Option::fromValue($this->findOneBy(
            array('name' => $key)
        ))->getOrCall(function () use ($key) {
            $config = new Config();
            $config->setName($key);
            return $config;
        });
    }
}
