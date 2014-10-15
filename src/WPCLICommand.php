<?php

namespace WCM\WPCLIComposer;

use Composer\Script\Event;

class WPCLICommand
{
	public static function postPackageInstall( Event $event )
	{
		print_r( func_get_args() );
	}
}