<?php
/**
 * This file belongs to whatever project.  Just quickly showing a sample of a file level
 * doc block
 */
namespace HelloWorld;

/**
 * This is a class level docblock that quickly describes the class below.
 */
class SayHello
{
    /**
     * Method level docblock gives a short summary of what the method does
     *
     * @return string
     */
    public static function world()
    {
        return 'Hello World, Composer!';
    }

    /**
     * Method says hello to a name
     *
     * @param $name <optional> Name of the person to say hello to
     * @return string
     */
    public static function name($name = null)
    {
        if (empty($name)) {
            return "You didn't tell me your name!";
        }
        return sprintf("Hell World, %s", ucwords($name));
    }
    /**
     * Quickly return a new instance of SayHello
     *
     * @return \HelloWorld\SayHellow
     */
    public static function returnObject()
    {
        return new SayHello();
    }
}
