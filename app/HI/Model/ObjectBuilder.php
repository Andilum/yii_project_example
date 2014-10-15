<?php

namespace HI\Model;

/**
 * Базовый класс-сборщик
 */

interface ObjectBuilding {
    /**
     * @param array $rawData
     * @return CTypedList
     * @throws \CException 
     */
    public static function buildList($rawData);
    
    /**
     * @param array $rawData
     * @return CModel
     * @throws \CException 
     */
    public static function buildOne($rawData);
}

