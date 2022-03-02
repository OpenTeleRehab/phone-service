<?php

namespace App\Helpers;

/**
 * @package App\Helpers
 */
class ApiHelper
{
    /**
     * @param $stage
     * @param $apiName
     * @param $domain
     *
     * @return string
     */
    public static function createApiUrl($stage, $apiName, $domain)
    {
        $apiUrl = '';
        switch ($stage) {
            case 'local':
                if ($apiName == 'websocket') {
                    $apiUrl = 'wss://' . $stage . '-hi-chat.' . $domain . '/websocket';
                    break;
                } else if ($apiName == 'chat') {
                    $apiUrl = 'https://' . $stage . '-hi-chat.' . $domain;
                    break;
                } else {
                    $apiUrl = 'https://' . $stage . '-hi-' . $apiName . '.' . $domain . '/api/' . $apiName;
                    break;
                }
            case 'demo':
                if ($apiName == 'websocket') {
                    $apiUrl = 'wss://' . $stage . '-chat-' . $domain . '/websocket';
                    break;
                } else if ($apiName == 'chat') {
                    $apiUrl = 'https://' . $stage . '-chat-' . $domain;
                    break;
                } else {
                    $apiUrl = 'https://' . $stage . '-' . $apiName . '-' . $domain . '/api/' . $apiName;
                    break;
                }
            case 'live':
                if ($apiName == 'websocket') {
                    $apiUrl = 'wss://' . 'chat.' . $domain . '/websocket';
                    break;
                } else if ($apiName == 'chat') {
                    $apiUrl = 'https://' . 'chat.' . $domain;
                    break;
                } else {
                    $apiUrl = 'https://' . $apiName . '.' . $domain . '/api/' . $apiName;
                    break;
                }
            default:
        }
        return $apiUrl;
    }
}
