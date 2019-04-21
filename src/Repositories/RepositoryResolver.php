<?php


namespace RandomState\Orm\Repositories;


use Doctrine\ORM\EntityManagerInterface;

class RepositoryResolver
{
	/**
	 * @var string
	 */
	protected $entity;

	/**
	 * @var \Closure
	 */
	protected $factory;

	/**
	 * @var RepositoryInterface[$managerName]
	 */
	protected $repositories = [];

	/**
	 * RepositoryResolver constructor.
	 * @param $document
	 * @param \Closure $closure
	 */
	public function __construct($document, \Closure $closure)
	{
		$this->document = $document;
		$this->factory = $closure;
	}

	public function getRepository(EntityManagerInterface $entityManager)
	{
		$managerName = spl_object_hash($entityManager);
		if (!isset($this->repositories[$managerName])) {
			$factory = $this->factory;
			$this->repositories[$managerName] = $factory($entityManager, $entityManager->getUnitOfWork(), $entityManager->getClassMetadata($this->document));
		}
		return $this->repositories[$managerName];
	}

	/**
	 * @return \Closure
	 */
	public function getFactory()
	{
		return $this->factory;
	}
}