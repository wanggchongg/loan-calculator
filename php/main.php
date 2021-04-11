<?php

// usage:  php main.php --bj=1750000 --ll=0.0465 --qs=360 --cm=minTouZiLiLv --qse=120
// bj=本金，ll=利率，qs=期数，cm=命令，qse=提前还款期数

$argv = getopt('', ['bj:','ll:', 'qs:', 'cm:', 'qse:']);
if (empty($argv['bj']) || empty($argv['ll']) || empty($argv['qs'])) {
    echo 'lack of argv: ' . print_r($argv, true). PHP_EOL;
    die();
}

$obj  = new LoanCalculator(intval($argv['bj']), floatval($argv['ll']), intval($argv['qs']));
switch ($argv['cm']) {
    case 'minTouZiLiLv':
        echo $obj->calculateMinTouZiLiLvFromDiffTwoWay(intval($argv['qse'])) . PHP_EOL;
        break;
    case 'zongErQiShu':
        echo $obj->calculateQiShuFromDiffYiHuanZongEr() . PHP_EOL;
        break;
    case 'benJinQiShu':
        echo $obj->calculateQiShuFromDiffShengYuBenJin() . PHP_EOL;
        break;
    case 'detail2CSV':
        $obj->generateDetailToCSV();
        break;
    case 'detail2String':
    default:
        echo $obj->generateDetailToString() . PHP_EOL;
        break;
}