<?php


namespace RandomState\Orm\Repositories;


interface RepositoryMappingProvider
{
	/**
	 * Should return a $entityClass => $repositoryResolveClosure / $repoClassName
	 *
	 * @return \Closure[]
	 */
	public function getRepositories();
}