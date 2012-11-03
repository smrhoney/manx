<?php

define('MANX_VERSION', '2.0.3');

interface IManx
{
    public function getDatabase();
    public function loginUser($user, $password);
    public function logout();
    public function getUserFromSession();
    public function addPublication($user, $company, $part, $pubDate, $title,
        $publicationType, $altPart, $revision, $keywords, $notes, $abstract,
        $languages);
}
