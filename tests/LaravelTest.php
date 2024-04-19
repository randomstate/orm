<?php


namespace RandomState\Orm\Tests;



use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use RandomState\Orm\OrmServiceProvider;
use Tests\TestCase;

class LaravelTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['APP_BASE_PATH'] = __DIR__ . '/../vendor/laravel/laravel';
        parent::setUp();

        $this->app['config']->set('app.key', Str::random(32));
        $this->app->register(OrmServiceProvider::class);
    }

    #[test]
    public function can_instantiate_entity_manager()
    {
        $em = $this->app->make(EntityManagerInterface::class);

        $this->assertInstanceOf(EntityManagerInterface::class, $em);
    }

    #[test]
    public function laravel_boots_up()
    {
        config()->set('session.driver', 'file');

        $this
            ->withoutExceptionHandling()
            ->get('/')
            ->assertok();
    }
}