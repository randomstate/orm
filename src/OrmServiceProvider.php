<?php


namespace RandomState\Orm;


use RandomState\Orm\Laravel\Traits\Config;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Extensions\BeberleiExtensionsServiceProvider;
use LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider;
use LaravelDoctrine\ORM\DoctrineServiceProvider;

class OrmServiceProvider extends ServiceProvider
{
	use Config;

	protected $repositories = [];

	public function boot()
	{

	}

	public function register()
	{
		$this->app->register(DoctrineServiceProvider::class);
		if(count($this->getConfig('extensions')) > 0)
		{
			$this->app->register(GedmoExtensionsServiceProvider::class);
			$this->app->register(BeberleiExtensionsServiceProvider::class);
		}

		$this->app->register(OrmRepositoryServiceProvider::class);
	}
}