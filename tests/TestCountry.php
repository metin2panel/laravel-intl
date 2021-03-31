<?php namespace Depiedra\LaravelIntl\Tests;

use Orchestra\Testbench\TestCase;
use Depiedra\LaravelIntl\Facades\Country;
use Depiedra\LaravelIntl\ServiceProvider;

class TestCountry extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $application
     * @return array
     */
    protected function getPackageProviders($application)
    {
        return [ServiceProvider::class];
    }

    public function setUp()
    {
        require_once __DIR__.'/../src/helpers.php';

        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(__DIR__ . '/..');
    }

    public function testHelper()
    {
        $this->assertEquals('Belgium', country('BE'));
        $this->assertEquals('Depiedra\LaravelIntl\Country', get_class(country()));
    }

    public function testHelperIsInSyncWithFacade()
    {
        Country::setLocale('foo');
        $this->assertEquals('foo', country()->getLocale());
    }

    public function testLocaleCanBeChanged()
    {
        $this->app->setLocale('nl');
        $this->assertEquals('België', Country::name('BE'));

        Country::setLocale('en');
        $this->assertEquals('Belgium', Country::name('BE'));
    }

    public function testFallbackLocaleIsUsed()
    {
        $country = Country::setLocale('foo');
        $country->setFallbackLocale('fr');
        $this->assertEquals('Belgique', $country->name('BE'));
    }

    public function testLocaleCanBeTemporarilyChanged()
    {
        $this->app->setLocale('nl');
        $name = Country::usingLocale('en', function($country) {
            return Country::name('BE');
        });

        $this->assertEquals('nl', Country::getLocale());
        $this->assertEquals('Belgium', $name);
    }

    public function testAll()
    {
        $countries = Country::all();
        $this->assertArraySubset(['BE' => 'Belgium', 'FR' => 'France'], $countries);

        $countries = Country::setLocale('nl')->all();
        $this->assertArraySubset(['BE' => 'België', 'FR' => 'Frankrijk'], $countries);
    }

    public function testName()
    {
        $country = Country::name('BE');
        $this->assertEquals('Belgium', $country);
    }
}
