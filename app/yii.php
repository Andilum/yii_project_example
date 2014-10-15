<?php

/**
 * Class Yii
 *
 * This is reloaded Yii class for loading WebApplication instead CWebApplication
 */
Class Yii extends YiiBase
{

    public static function createWebApplication($config = null)
    {
        return self::createApplication('WebApplication', $config);
    }

    /**
     * @return WebApplication
     */
    public static function app()
    {
        return parent::app();
    }
}