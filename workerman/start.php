#!/usr/bin/env php
<?php
/**
 * VPS Hosting Daemon - Notes   -(incomplete) +(completed)
 * =======================================================
 *
 *  +  it acts as a permanent client connecting to our central websocket server
 *  +  it will use ssl for its communications and add some authentication type layer of security
 *  +  the 'get vps list' will be put on a much slower timer as it doesnt need to be updating that often.  after any
 *       queue commands are received though it should follow it up with another 'get vps list'.   apart from that when
 *       it does get a listing it should store the results internally as well and if they are the same as the last thing
 *       we sent then dont bother pushing a new list update
 *  +  asynchronous.  if we're doing some command that takes a while run it as a piped
 *       process or similar so we can handle it asynchronously and the workers dont get backed up.
 *  +  improved bandwidth.   bandwidth needs to be sending an update every minute, at lesat tentativeley .   This
 *       will work well with RRD although not sure if we're going to use that yet or not.. despite already coding
 *       things to store it and all that..will have to see how it plays out on the disk IO
 *  -  running commands.  there should be a message type that allows the central server to push a command to it to
 *       run and proxy the output through the ws.  2 examples of this are the VMStat live graph i have setup to have
 *       the server run 'vmstat 1' in a pipe and send the output over the ws connection and accept input from it to
 *       redirect to the pipe.   The other example is the PHPTTY page which provides basic terminal emulation to a
 *       browser.   on this side a command would be run and i/o redirected over the ws connection.
 *  -  instead of periodically checking for new queued items it will get sent notifications via its client
 *       ws connection for queues.   the central server will periodically check the queue and if theres something
 *       new itw ill push notifications to the appropriate server.   this should cut back on queries and resource usage.
 *  -  easily updated.  needs a mechanism to allow it to easily received updates and reload itself w/ the new updates.
 *  -  make it easily expandable. eventually we'll want to easily add custom commands and handling for the chat/ws side to
 *       be able to use so things should be setup in a way that allows this.
 *
 *
 * Arnold Schwarzenegger -- Get To The Chopper!
 *  https://www.youtube.com/watch?v=Xs_OacEq2Sk&feature=youtu.be&t=4s
 */

use Workerman\Worker;

include_once __DIR__.'/src/bootstrap.php';

define('GLOBAL_START', 1); // The flag is globally activated
Worker::$stdoutFile = __DIR__.'/stdout.log';
foreach (glob(__DIR__.'/src/Workers/*.php') as $start_file) {
	require_once $start_file;
}

Worker::runAll();
