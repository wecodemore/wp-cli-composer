<?php

namespace WCM\WPCLI\Autocomplete\Test;

use Composer\IO\NullIO;
use Composer\Package\Package;

use PHPUnit\Framework\TestCase;
use WCM\WPCLI\Autocomplete\Setup;

class Test extends TestCase
{
	private static $file, $handler;

	public static function setUpBeforeClass() {
		self::$file    = __DIR__."/.bash_profile";
		self::$handler = fopen( self::$file, 'a' );
	}

	public static function tearDownAfterClass() {
		fclose( self::$handler );
		unlink( self::$file );
	}

	public function testExtraExists()
	{
		$io  = new NullIO;

		$pkg = new Package( 'test/test', 1, 1 );
		$extra = [ 'bash-profile-dir' => '/home/foo', ];
		$pkg->setExtra( $extra );
		$this->assertTrue( Setup::checkExtraEntry( $io, $pkg ) );

		$empty = new Package( 'test/test', 2, 2 );
		$empty->setExtra( [] );
		$this->assertFalse( Setup::checkExtraEntry( $io, $empty ) );
	}

	public function testPackageExists()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );
		$deps = [ 'wp-cli/wp-cli' => '*', ];
		$pkg->setRequires( $deps );
		$pkg->setDevRequires( $deps );
		$this->assertTrue( Setup::checkDependencies( $io, $pkg ) );
	}

	public function testPackageNotExists()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );
		$pkg->setRequires( [] );
		$pkg->setDevRequires( [] );
		$this->assertFalse( Setup::checkDependencies( $io, $pkg ) );
	}

	public function testAppendCmdOutput()
	{
		$io  = new NullIO;
		$pkg = new Package( 'test/test', 1, 1 );

		$extra = [ 'bash-profile-dir' => dirname( self::$file ), ];
		$pkg->setExtra( $extra );

		# Is appendable?
		$this->assertFileExists( self::$file );

		# Pretty much the same as the internal behavior.
		# This test as well tests if the tmp file setup worked out in the setup method.
		$source = file_get_contents( __DIR__.'/../ci/.wpcli_profile' );
		$result = file_put_contents( self::$file, $source, FILE_APPEND );
		$this->assertInternalType('integer', $result);
		$this->assertTrue( false !== strpos( $source, file_get_contents( self::$file ) ) );

		# Make and add file, check against existing data, …
		$this->assertTrue( Setup::appendCmd( $io, $pkg ) );
		# …repetitive calls do not duplicate the appended auto-complete script.
		$this->assertFalse( Setup::appendCmd( $io, $pkg ) );
	}
}
