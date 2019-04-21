<?php


namespace RandomState\Orm;


use Doctrine\ORM\EntityManagerInterface;
use RandomState\Orm\Laravel\Traits\Config;
use RandomState\Orm\Repositories\RepositoryMappingProvider;
use RandomState\Orm\Repositories\RepositoryResolverRegistry;
use RandomState\Orm\Repositories\ResolverCallbacks;
use Doctrine\ORM\Repository\RepositoryFactory;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class OrmRepositoryServiceProvider extends ServiceProvider
{
	use Config, ResolverCallbacks;

	protected $repositories = [];

	public function register()
	{
		if($repos = $this->getConfig('repositories.map', []))
		{
			$this->repositories = $repos + $this->repositories;
		}

		if($provider = $this->getConfig('repositories.provider'))
		{
			/** @var RepositoryMappingProvider $provider */
			$provider = $this->app->make($provider);
			$this->repositories = $provider->getRepositories() + $this->repositories;
		}

		$this->app->singleton(RepositoryResolverRegistry::class);
		$this->app->bind(RepositoryFactory::class, RepositoryResolverRegistry::class);

		/*
		 * Add the custom repositories and their resolvers to the registry after boot.
		 */
		$this->app->afterResolving(RepositoryResolverRegistry::class, function(RepositoryResolverRegistry $resolverRegistry){
			foreach($this->repositories as $document => $repository)
			{
				$resolverRegistry->addResolver($document, $this->getResolverFor($repository));
			}
		});


		/*
		* Bind repository class abstracts to the real implementations,
		* which are only ever resolved by the RepositoryResolverRegistry.
		*/
		foreach($this->repositories as $entity => $repository)
		{
			$this->app->singleton($repository, function(Container$app) use($entity) {
				/** @var RepositoryFactory $factory */
				$factory = $app->make(RepositoryFactory::class);
				return $factory->getRepository($app->make(EntityManagerInterface::class), $entity);
			});
		}
	}
}