<?php

use Appizy\Core\Command\ConvertCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DomCrawler\Crawler;

class WebAppIntegrationTest extends PHPUnit_Framework_TestCase
{
    /** @var  Crawler */
    protected $crawler;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new ConvertCommand());

        $command = $application->find('convert');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'source'  => 'tests/fixtures/demo-appizy.ods'
        ));

        $generatedHtml = file_get_contents('tests/fixtures/app.html');
        $this->crawler = new Crawler($generatedHtml);
    }

    public function testBasicDOMComponents()
    {
        $this->assertEquals(count($this->crawler->filter('body')), 1);
        $this->assertEquals(count($this->crawler->filter('#appizy')), 1);
    }

    protected function tearDown()
    {
        parent::tearDown();
        exec('rm tests/fixtures/*.html');
        exec('rm tests/fixtures/*.js');
    }
}