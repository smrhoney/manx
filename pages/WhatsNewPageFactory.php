<?php

require_once 'File.php';
require_once 'IWhatsNewPageFactory.php';
require_once 'IDateTimeProvider.php';
require_once 'UrlInfo.php';
require_once 'UrlTransfer.php';

class WhatsNewPageFactory implements IWhatsNewPageFactory
{
    function createUrlInfo($url)
    {
        return new UrlInfo($url);
    }

    function createUrlTransfer($url)
    {
        return new UrlTransfer($url);
    }

    function getCurrentTime()
    {
        date_default_timezone_set(TIME_ZONE);
        return time();
    }
}
