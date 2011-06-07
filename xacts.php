<?php

require_once( 'iCalcreator.class.php' );

$ledger = "/usr/local/bin/ledger -f /Users/bettse/Dropbox/Finances/current.lgr";
$daily_balance = ' -F "%(date)\t%(payee)\t%(amount)\n" -d "d>=[today]-14 & d < [tomorrow]"  --sort d reg "FirstTech:Checking"';
$output = "";
exec("$ledger $daily_balance", $output);

$v = new vcalendar();
// create a new calendar instance
$v->setConfig( 'unique_id', 'ataraxia.ericbetts.org' );
// set Your unique id

$v->setProperty( 'method', 'PUBLISH' );
// required of some calendar software
$v->setProperty( "x-wr-calname", "Checking Events" );
// required of some calendar software
$v->setProperty( "X-WR-CALDESC", "Daily events of my checking account as reported by Ledger" );
// required of some calendar software
$v->setProperty( "X-WR-TIMEZONE", "US/Pacific" );
// required of some calendar software


foreach ($output as $line){
    //make into key-value pairs
    $tmp = explode("\t", $line);
//    print_r($tmp);
    $date = $tmp[0];
    $event = $tmp[1] . "\t" . $tmp[2];

    // add event to calendar
    $vevent = new vevent();
    $vevent->setProperty( 'dtstart', $date, array('VALUE' => 'DATE'));
    // alt. date format, now for an all-day event
    $vevent->setProperty( 'summary', $event );
    $v->setComponent ( $vevent );
}

$v->returnCalendar();
// redirect calendar file to browser

?>


