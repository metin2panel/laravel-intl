<?php namespace Depiedra\LaravelIntl\Tests;

use Orchestra\Testbench\TestCase;
use Depiedra\LaravelIntl\Facades\Currency;
use Depiedra\LaravelIntl\ServiceProvider;

class TestCurrency extends TestCase
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

    public function testHelper()
    {
        $this->assertEquals('US Dollar', currency('USD'));
        $this->assertEquals('Depiedra\LaravelIntl\Currency', get_class(currency()));
        $this->assertEquals('€1,234.00', currency(1234, 'EUR'));
    }

    public function testHelperIsInSyncWithFacade()
    {
        Currency::setLocale('foo');
        $this->assertEquals('foo', currency()->getLocale());
    }

    public function testLocaleCanBeChanged()
    {
        $this->app->setLocale('nl');
        $this->assertEquals('Amerikaanse dollar', Currency::name('USD'));
        $this->assertEquals('€ 1.234,00', Currency::format(1234, 'EUR'));

        Currency::setLocale('en');
        $this->assertEquals('US Dollar', Currency::name('USD'));
    }

    public function testFallbackLocaleIsUsed()
    {
        $currency = Currency::setLocale('foo');
        $currency->setFallbackLocale('fr');
        $this->assertEquals('1 234,00 €', $currency->format(1234, 'EUR'));
    }

    public function testLocaleCanBeTemporarilyChanged()
    {
        $this->app->setLocale('nl');
        $name = Currency::usingLocale('en', function($currency) {
            return Currency::name('USD');
        });

        $this->assertEquals('nl', Currency::getLocale());
        $this->assertEquals('US Dollar', $name);
    }

    public function testGet()
    {
        $currency = Currency::get('USD');
        $this->assertEquals('US Dollar', $currency);
    }

    public function testAll()
    {
        $currencies = Currency::all();
        $this->assertArraySubset(['EUR' => 'Euro', 'USD' => 'US Dollar'], $currencies);

        $currencies = Currency::setLocale('nl')->all();
        $this->assertArraySubset(['EUR' => 'Euro', 'USD' => 'Amerikaanse dollar'], $currencies);
    }

    public function testName()
    {
        $currency = Currency::name('USD');
        $this->assertEquals('US Dollar', $currency);
    }

    public function testSymbol()
    {
        $currency = Currency::symbol('USD');
        $this->assertEquals('$', $currency);
    }

    public function testFormat()
    {
        $currency = Currency::format(1234, 'EUR');
        $this->assertEquals('€1,234.00', $currency);
    }

    public function testFormatAccounting()
    {
        $currency = Currency::formatAccounting(-1234, 'EUR');
        $this->assertEquals('(€1,234.00)', $currency);
    }

    public function testParse()
    {
        $this->app->setLocale('nl');
        $currency = Currency::parse('€ 1.234,50', 'EUR');
        $this->assertEquals(1234.5, $currency);
    }
}
