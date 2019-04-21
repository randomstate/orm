<?php


namespace RandomState\Orm\Repositories;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Doctrine\ORM\Repository\RepositoryFactory;

class RepositoryResolverRegistry implements RepositoryFactory
{
	/**
	 * @var RepositoryResolver[]
	 */
	protected $resolvers = [];

	public function addResolver($entity, \Closure $closure)
	{
		$this->resolvers[$entity] = new RepositoryResolver($entity, $closure);
	}

	public function getResolver($entity)
	{
		if (!isset($this->resolvers[$entity])) {
			return null;
		}

		return $this->resolvers[$entity];
	}

	/**
	 * @param EntityManager | EntityManagerInterface $entityManager
	 * @param string $entityName
	 * @return \Doctrine\Common\Persistence\ObjectRepository
	 */
	public function getRepository(EntityManagerInterface $entityManager, $entityName)
	{
		if (!isset($this->resolvers[$entityName])) {
			return (new DefaultRepositoryFactory())->getRepository($entityManager, $entityName);
		}

		return $this->resolvers[$entityName]->getRepository($entityManager);
	}
}