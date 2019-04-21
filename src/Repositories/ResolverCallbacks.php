<?php


namespace RandomState\Orm\Repositories;


use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

trait ResolverCallbacks
{
	public function getResolverFor($repository)
	{
		$resolver = $repository;
		if(is_string($repository))
		{
			$resolver = function(EntityManagerInterface $entityManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) use($repository) {
				return new $repository($entityManager, $classMetadata, $unitOfWork);
			};
		}
		return $resolver;
	}
}