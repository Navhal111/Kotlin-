<?php

Namespace Tests\Unit\HelloWorld;

use \PHPUnit_Framework_TestCase;
use \HelloWorld\SayHello;

class SayHelloTest extends PHPUnit_Framework_TestCase {
    public function testWorld()
    {
      $output = SayHello::world();
      $this->assertEquals("Hello World, Composer!", $output, "Failed to say hello to the world");
    }

    public function testNameEmpty()
    {
        $output = SayHello::name();
        $this->assertEquals("You didn't tell me your name!", $output);
    }

    public function testName()
    {
        $output = SayHello::name("steve");
        $this->assertEquals("Hello Steve!", $output);
    }

}
