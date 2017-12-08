<?php
namespace Tests\Integration\HelloWorld;

use HelloWorld\SayHello;
use PHPUnit_Framework_TestCase;

class SayHelloTest extends PHPUnit_Framework_TestCase
{
    public function testWorldNotLowerCase()
    {
        $output = SayHello::world();
        $this->assertNotEquals($output, strtolower($output), "The String is Lower Case");
    }
    public function testWorldNotMisspelled()
    {
        $output = SayHello::world();
        $this->assertContains('Composer', $output, "Composer is misspelt");
    }

    public function testNameUppercased()
    {
        $output = SayHello::name('steve johnson');
        $this->assertContains("Steve", $output);
        $this->assertNotContains("steve", $output);
        $this->assertContains("Johnson", $output);
        $this->assertNotContains("johnson", $output);

    }
}
