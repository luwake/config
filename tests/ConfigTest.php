<?php
namespace Noodlehaus;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadJson
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Syntax error
     */
    public function testLoadWithInvalidJson()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.json');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadXml
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Opening and ending tag mismatch: name line 4
     */
    public function testLoadWithInvalidXml()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.xml');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadYaml
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Error parsing YAML file
     */
    public function testLoadWithInvalidYaml()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.yaml');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadIni
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage syntax error, unexpected $end, expecting ']'
     */
    public function testLoadWithInvalidIni()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.ini');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadPhp
     * @expectedException        Noodlehaus\Exception\UnsupportedFormatException
     * @expectedExceptionMessage PHP file does not return an array
     */
    public function testLoadWithInvalidPhp()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.php');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadPhp
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage PHP file threw an exception
     */
    public function testLoadWithExceptionalPhp()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error-exception.php');
    }

    /**
     * @covers                   Noodlehaus\Config::__construct
     * @expectedException        Noodlehaus\Exception\UnsupportedFormatException
     * @expectedExceptionMessage Unsupported configuration format
     */
    public function testLoadWithUnsupportedFormat()
    {
        $config = Config::load(__DIR__ . '/mocks/fail/error.lib');
    }

    /**
     * @covers                   Noodlehaus\Config::__construct
     * @expectedException        Noodlehaus\Exception\FileNotFoundException
     * @expectedExceptionMessage Configuration file: [ladadeedee] cannot be found
     */
    public function testConstructWithInvalidPath()
    {
        $config = new Config('ladadeedee');
    }
    
    /**
     * @covers                   Noodlehaus\Config::__construct
     * @expectedException        Noodlehaus\Exception\EmptyDirectoryException
     */
    public function testConstructWithEmptyDirectory()
    {
        $config = new Config(__DIR__ . '/mocks/empty');
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadPhp
     */
    public function testConstructWithPhpArray()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config.php');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadPhp
     */
    public function testConstructWithPhpCallable()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config-exec.php');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadIni
     */
    public function testConstructWithIni()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config.ini');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadJson
     */
    public function testConstructWithJson()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config.json');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadXml
     */
    public function testConstructWithXml()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config.xml');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadYaml
     */
    public function testConstructWithYaml()
    {
        $config = new Config(__DIR__ . '/mocks/pass/config.yaml');
    }
    
    /**
     * @covers       Noodlehaus\Config::__construct
     */
    public function testConstructWithArray()
    {
        $paths = array(__DIR__ . '/mocks/pass/config.xml', __DIR__ . '/mocks/pass/config2.json');
        $config = new Config($paths);
    }
    
    /**
     * @covers                   Noodlehaus\Config::__construct
     */
    public function testConstructWithDirectory()
    {
        $config = new Config(__DIR__ . '/mocks/dir');
    }


    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGet($config)
    {
        $this->assertEquals('localhost', $config->get('host'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetWithDefaultValue($config)
    {
        $this->assertEquals(128, $config->get('ttl', 128));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNestedKey($config)
    {
        $this->assertEquals('configuration', $config->get('application.name'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNestedKeyWithDefaultValue($config)
    {
        $this->assertEquals(128, $config->get('application.ttl', 128));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNonexistentKey($config)
    {
        $this->assertNull($config->get('proxy'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNonexistentNestedKey($config)
    {
        $this->assertNull($config->get('proxy.name'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetReturnsArray($config)
    {
        $this->assertArrayHasKey('name', $config->get('application'));
        $this->assertEquals('configuration', $config->get('application.name'));
        $this->assertCount(2, $config->get('application'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSet($config)
    {
        $config->set('region', 'apac');
        $this->assertEquals('apac', $config->get('region'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetNestedKey($config)
    {
        $config->set('location.country', 'Singapore');
        $this->assertEquals('Singapore', $config->get('location.country'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetArray($config)
    {
        $config->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase'
        ));
        $this->assertTrue(is_array($config->get('database')));
        $this->assertEquals('localhost', $config->get('database.host'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetAndUnsetArray($config)
    {
        $config->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase'
        ));
        $this->assertTrue(is_array($config->get('database')));
        $this->assertEquals('localhost', $config->get('database.host'));
        $config->set('database.host', null);
        $this->assertNull($config->get('database.host'));
        $config->set('database', null);
        $this->assertNull($config->get('database'));
    }

    /**
     * @covers       Noodlehaus\Config::offsetGet
     * @dataProvider providerConfig
     */
    public function testOffsetGet($config)
    {
        $this->assertEquals('localhost', $config['host']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetGet
     * @dataProvider providerConfig
     */
    public function testOffsetGetNestedKey($config)
    {
        $this->assertEquals('configuration', $config['application.name']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetExists
     * @dataProvider providerConfig
     */
    public function testOffsetExists($config)
    {
        $this->assertTrue(isset($config['host']));
    }

    /**
     * @covers       Noodlehaus\Config::offsetExists
     * @dataProvider providerConfig
     */
    public function testOffsetExistsReturnsFalseOnNonexistentKey($config)
    {
        $this->assertFalse(isset($config['database']));
    }

    /**
     * @covers       Noodlehaus\Config::offsetSet
     * @dataProvider providerConfig
     */
    public function testOffsetSet($config)
    {
        $config['newkey'] = 'newvalue';
        $this->assertEquals('newvalue', $config['newkey']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetUnset
     * @dataProvider providerConfig
     */
    public function testOffsetUnset($config)
    {
        unset($config['application']);
        $this->assertNull($config['application']);
    }
    
    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerComposedConfig
     */
    public function testGetReturnsArrayMergedArray($config)
    {
        $this->assertCount(4, $config->get('servers'));
    }

    /**
     * Provides names of example configuration files
     */
    public function providerConfig()
    {
        return array_merge(
            array(
                array(new Config(__DIR__ . '/mocks/pass/config-exec.php')),
                array(new Config(__DIR__ . '/mocks/pass/config.ini')),
                array(new Config(__DIR__ . '/mocks/pass/config.json')),
                array(new Config(__DIR__ . '/mocks/pass/config.php')),
                array(new Config(__DIR__ . '/mocks/pass/config.xml')),
                array(new Config(__DIR__ . '/mocks/pass/config.yaml'))
            ),
            $this->providerComposedConfig()
        );
    }
    
    /**
     * Provides names of example configuration files (for array and directory)
     */
    public function providerComposedConfig()
    {
        return array(
            array(
                new Config(
                    array(
                        __DIR__ . '/mocks/pass/config2.json',
                        __DIR__ . '/mocks/pass/config.yaml'
                    )
                ),
                new Config(__DIR__ . '/mocks/dir/')
            )
        );
    }
}
