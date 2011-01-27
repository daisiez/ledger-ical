<?php

require_once( 'iCalcreator.class.php' );

$ledger = "/usr/local/bin/ledger -f /Users/bettse/Dropbox/Finances/current.lgr";
$daily_balance = '-w -J -c -p "daily" -d "d>=[last month] & d < [next month]+30"  --sort d reg "OSU Fed:Checking"';
$output = "";
exec("$ledger $daily_balance", $output);

$v = new vcalendar();
// create a new calendar instance
$v->setConfig( 'unique_id', 'ataraxia.ericbetts.org' );
// set Your unique id

$v->setProperty( 'method', 'PUBLISH' );
// required of some calendar software
$v->setProperty( "x-wr-calname", "Checking Balance" );
// required of some calendar software
$v->setProperty( "X-WR-CALDESC", "The daily balance of my checking account as reported by Ledger" );
// required of some calendar software
$v->setProperty( "X-WR-TIMEZONE", "US/Pacific" );
// required of some calendar software

foreach ($output as $line){
    //make into key-value pairs
    $tmp = explode(" ", $line);
    $date = $tmp[0];
    $checking_value = $tmp[1];

    // add event to calendar
    $vevent = new vevent();
    $vevent->setProperty( 'dtstart', $date, array('VALUE' => 'DATE'));
    // alt. date format, now for an all-day event
    $vevent->setProperty( 'summary', $checking_value );
    $v->setComponent ( $vevent );
}

$v->returnCalendar();
// redirect calendar file to browser

?>


