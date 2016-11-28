<?php

namespace WCM\WPCLIComposer\Test;

use Composer\IO\NullIO;
use Composer\Package\Package;

use PHPUnit\Framework\TestCase;
use WCM\WPCLIComposer\WPCLICommand;

class Test extends TestCase
{
	private static $file, $handler;

	public static function setUpBeforeClass() {
		self::$file    = __DIR__."/.bash_profile";
		self::$handler = fopen( self::$file, 'a' );
	}

	public static function tearDownAfterClass() {
		#fclose( self::$handler );
		#unlink( self::$file );

	}

	public function testExtraExists()
	{
		$io  = new NullIO;

		$pkg = new Package( 'test/test', 1, 1 );
		$extra = [ 'bash-profile-dir' => '/home/foo', ];
		$pkg->setExtra( $extra );
		$this->assertTrue( WPCLICommand::checkExtraEntry( $io, $pkg ) );

		$empty = new Package( 'test/test', 2, 2 );
		$empty->setExtra( [] );
		$this->assertFalse( WPCLICommand::checkExtraEntry( $io, $empty ) );
	}

	public function testPackageExists()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );
		$deps = [ 'wp-cli/wp-cli' => '*', ];
		$pkg->setRequires( $deps );
		$pkg->setDevRequires( $deps );
		$this->assertTrue( WPCLICommand::checkDependencies( $io, $pkg ) );
	}

	public function testPackageNotExists()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );
		$pkg->setRequires( [] );
		$pkg->setDevRequires( [] );
		$this->assertFalse( WPCLICommand::checkDependencies( $io, $pkg ) );
	}

	public function testAppendCmdOutput()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );

		$extra = [ 'bash-profile-dir' => dirname(self::$file), ];
		$pkg->setExtra( $extra );

		$this->assertFalse( WPCLICommand::appendCmd( $io, $pkg ) );
	}
}
