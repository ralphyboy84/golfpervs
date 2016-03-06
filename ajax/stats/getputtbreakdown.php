<?php

session_start();

require_once ("../../globals/globals.php");
require_once ("../../coreClasses/roundInfo.class.php");
require_once ("../../coreClasses/rounds.class.php");
$roundInfo = new roundInfo();
$roundInfo->setRoundId($_GET['id']);
$info['This Round'] = $roundInfo->getPuttBreakDown();


$rounds = new rounds();
$rounds->setUser("ralph");
$rounds->setYear(date("Y"));
//$rounds->setYear(date("Y"));
$year = $rounds->getRounds();

if ($year['res']) {
    foreach ($year['res'] as $y) {
        $nr = new roundInfo();
        $nr->setRoundId($y['roundid']);
        $temp = $nr->getPuttBreakDown();
        
        for ($x=0; $x<5; $x++) {
            $yrtemp[$x] = $temp[$x] + $yrtemp[$x];
        }
    }
}

for ($x=0; $x<5; $x++) {
    $yrtemp[$x] = round($yrtemp[$x] / sizeof($year['res']), 2);
}


$info['Year Average'] = $yrtemp;


$rounds = new rounds();
$rounds->setUser("ralph");
//$rounds->setYear(date("Y"));
$year = $rounds->getRounds();

if ($year['res']) {
    foreach ($year['res'] as $y) {
        $nr = new roundInfo();
        $nr->setRoundId($y['roundid']);
        $temp = $nr->getPuttBreakDown();
        
        for ($x=0; $x<5; $x++) {
            $yrtemp[$x] = $temp[$x] + $yrtemp[$x];
        }
    }
}

for ($x=0; $x<5; $x++) {
    $yrtemp[$x] = round($yrtemp[$x] / sizeof($year['res']), 2);
}

$info['All Time Average'] = $yrtemp;

echo json_encode($info);
?>
