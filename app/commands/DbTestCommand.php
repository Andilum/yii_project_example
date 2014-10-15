<?php

/**
 * Команда тестирования скорости выборки из БД
 */
class DbTestCommand extends CConsoleCommand {
    public function run($args) {
        error_reporting(E_ALL ^ E_WARNING);
        
        echo "\nStart\n";
        
        $timeStart = microtime(true);
        
        $totalIterationTime = 0;
    
        for($i = 0; $i < 10; $i++) {
            $iterationStart = microtime(true);
        
            DictAllocation::initSearch()->getData(true);
            
            $iterationEnd = microtime(true);
            
            $totalIterationTime += $iterationEnd - $iterationStart;
        }
        
        $avgIterationTime = $totalIterationTime / $i;
        
        $totalTime = array("raw" => microtime(true) - $timeStart);
        
        $totalTime["h"] = floor($totalTime["raw"] / 3600);
        $totalTime["m"] = floor(($totalTime["raw"] - $totalTime["h"] * 3600) / 60);
        $totalTime["s"] = ($totalTime["raw"] - $totalTime["h"] * 3600 - $totalTime["m"] * 60);
        
        echo "Total time. H = {$totalTime['h']}; M = {$totalTime['m']}; S = {$totalTime['s']}; RAW = {$totalTime['raw']} sec\n";
        echo "Average iteration time. RAW = {$avgIterationTime} sec\n\n";
    }
}
