<?php

namespace WCM\WPCLIComposer;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use \Composer\Package\PackageInterface;

class WPCLICommand
{
	/**
	 * Append the WP-CLI auto-completion bash script to the users `~/.bash_profile`
	 * @param Event $event
	 * @return bool
	 */
	public static function postPackageInstall( Event $event )
	{
		$io = $event->getIO();

		$package = $event
			->getComposer()
			->getPackage();

		if ( ! self::checkExtraEntry( $io, $package ) )
			return false;

		if ( ! self::checkDependencies( $io, $package ) )
			return false;

		self::appendCmd( $io, $package );

		return true;
	}

	/**
	 * Check if the `.bash_profile` root dir exists. Prompt for a new one if it does not exist.
	 * @param IOInterface $io
	 * @param string      $dir
	 * @return mixed
	 */
	public static function getDir( IOInterface $io, $dir )
	{
		if ( ! is_dir( $dir ) )
		{
			$io->write( sprintf(
				'<info>The `.bash_profile` specified location specified in the composer.json file: %s does not exist.</info>',
				$dir
			) );
			$dir = $io->ask( '<info>Please provide the root directory for your `.bash_profile` file:</info> ', $dir );
			return self::getDir( $io, $dir );
		}

		return $dir;
	}


	/**
	 * @param \Composer\IO\IOInterface           $io
	 * @param \Composer\Package\PackageInterface $package
	 * @return bool
	 */
	public static function checkExtraEntry( IOInterface $io, PackageInterface $package )
	{
		$extra = $package->getExtra();
		if ( ! isset( $extra['bash-profile-dir'] ) )
		{
			$io->writeError( '<info>You must set "extra" : { "bash-profile-dir" } in your `composer.json` file. Skipping.</info>' );
			return false;
		}

		return true;
	}


	/**
	 * @param \Composer\IO\IOInterface           $io
	 * @param \Composer\Package\PackageInterface $package
	 * @return bool
	 */
	public static function checkDependencies( IOInterface $io, PackageInterface $package )
	{
		$search = 'wp-cli/wp-cli';
		$requires    = $package->getRequires();
		$requiresDev = $package->getDevRequires();

		if (
			isset( $requires[ $search ] )
			OR isset( $requiresDev[ $search ] )
		)
			return true;

		$io->writeError( '<info>This package is a dependency of `wp-cli/wp-cli`. Skipping.</info>' );
		return false;
	}


	/**
	 * @param \Composer\IO\IOInterface           $io
	 * @param \Composer\Package\PackageInterface $package
	 * @return bool Just to make unit testing easier
	 */
	public static function appendCmd( IOInterface $io, PackageInterface $package )
	{
		$extra  = $package->getExtra();
		$target = self::getDir( $io, $extra['bash-profile-dir'] );
		$target = "{$target}/.bash_profile";

		$source = "\n".file_get_contents( __DIR__.'/../ci/.wpcli_profile' );

		if (
			self::isAppendable( $source, $target )
			and false !== file_put_contents( $target, $source, FILE_APPEND )
		) {
			$io->write( '<info>Successfully appended WP-CLI auto-completion to your bash profile.</info>' );
			return true;
		}

		$io->write( '<info>No need to append anything to your bash profile.</info>' );
		return false;
	}

	/**
	 * @param string $source
	 * @param string $target
	 * @return bool
	 */
	private static function isAppendable( $source, $target )
	{
		return
			(
				file_exists( $target )
				and false === strpos( file_get_contents( $target ), $source )
			)
			or ! file_exists( $target );
	}
}