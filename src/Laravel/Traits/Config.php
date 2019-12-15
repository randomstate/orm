<?php


namespace RandomState\Orm\Laravel\Traits;


use Illuminate\Support\Arr;

trait Config
{
	/**
	 * @param string | null $key
	 * @param mixed | null $default
	 * @return mixed
	 */
	protected function getConfig($key = null, $default = null)
	{
		$key = $key !== null ? '.' . $key : null;
		return $this->getGlobalConfig($this->getConfigName() . $key, $default);
	}
	/**
	 * @param string | null $key
	 * @param mixed| null $default
	 * @return mixed
	 */
	protected function getGlobalConfig($key = null, $default = null)
	{
		$config = $this->app->make('config')->all();
		if ($key) {
			$config = Arr::get($config, $key, $default);
		}
		return $config;
	}
	/**
	 * @return string
	 */
	protected function getConfigName()
	{
		return 'doctrine';
	}
	/**
	 * @return string
	 */
	protected function getConfigPath()
	{
		return config_path($this->getConfigName() . '.php');
	}
}