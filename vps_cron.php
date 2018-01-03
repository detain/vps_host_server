#!/usr/bin/php -q
<?php
/**
 * VPS Functionality
 * @author Joe Huss <detain@interserver.net>
 * @copyright 2017
 * @package MyAdmin
 * @category VPS
 */
if (ini_get('date.timezone') == '')
{
	ini_set('date.timezone', 'America/New_York');
}
if ((isset($_ENV['SHELL']) && $_ENV['SHELL'] == '/bin/sh') && file_exists('/cron.vps.disabled'))
{
	exit;
}
$url = 'https://myvps2.interserver.net/vps_queue.php';
echo "[" . date('Y-m-d H:i:s') . "] Crontab Startup\n";
$cmd = dirname(__FILE__).'/vps_update_info.php;';
//echo "Running Command: $cmd\n";
echo `$cmd`;
$cmd = "curl --connect-timeout 60 --max-time 600 -k -d action=getnewvps '{$url}' 2>/dev/null;";
//echo "Running Command: $cmd\n";
$out = trim(`$cmd`);
if ($out != '')
{
	echo "Get New VPS Running:	$out\n";
	echo `$out`;
}
$cmd = dirname(__FILE__).'/vps_traffic_new.php;';
//echo "Running Command: $cmd\n";
echo `$cmd`;
$cmd = "curl --connect-timeout 60 --max-time 600 -k -d action=getslicemap '{$url}' 2>/dev/null;";
//echo "Running Command: $cmd\n";
$out = trim(`$cmd`);
//echo "Get IP List Running:	$out\n";
if ($out != '')
{
	echo `$out`;
}
if (!file_exists('/usr/sbin/vzctl'))
{
	$cmd = "curl --connect-timeout 60 --max-time 600 -k -d action=getipmap '{$url}' 2>/dev/null;";
	//echo "Running Command: $cmd\n";
	$out = trim(`$cmd`);
	//echo "Get IP List Running:	$out\n";
	if ($out != '')
	{
		echo `$out`;
	}
	$cmd = "curl --connect-timeout 60 --max-time 600 -k -d action=getvncmap '{$url}' 2>/dev/null;";
	//echo "Running Command: $cmd\n";
	$out = trim(`$cmd`);
	//echo "Get IP List Running:	$out\n";
	if ($out != '')
	{
		echo `$out`;
	}
}
$cmd = "curl --connect-timeout 60 --max-time 600 -k -d action=getqueue '{$url}' 2>/dev/null;";
//echo "Running Command: $cmd\n";
$out = trim(`$cmd`);
if ($out != '')
{
	echo "Get Queue Running:$out\n";
	echo `$out`;
}
$cmd = dirname(__FILE__).'/vps_get_list.php;';
//echo "Running Command: $cmd\n";
echo `$cmd`;
/*
ob_start();
$out = ob_get_contents();
ob_flush();
echo trim($out);
*/
