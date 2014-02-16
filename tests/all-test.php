<?php

class allTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite( 'all tests' );
        $suite->addTestFile( __DIR__ . '/CenaDemo/Models/Post_BasicTest.php' );
        $suite->addTestFile( __DIR__ . '/CenaDemo/Resource/Posting_BasicTest.php' );
        return $suite;
    }
}