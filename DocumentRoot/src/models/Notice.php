<?php

/**
 * Une notice
 *
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/enumNoticeStatus.php';

class Notice
{
    public function __construct(

        public  string $message,
        public NoticeStatus $status,
    ) {
    }
}
