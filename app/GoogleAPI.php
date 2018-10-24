<?php

namespace App;

use Google_Client;
use Google_Service_Directory;
use Storage;

class GoogleAPI
{

    public function setSessionUserGroups()
    {

        $client = $this->createDirectoryClient();

        $service = new Google_Service_Directory($client);
        $groups = collect($service->groups->listGroups(['userKey' => auth()->user()->email])->groups)->pluck('name');
        return session()->put('user_groups', $groups);

    }

    protected function createDirectoryClient()
    {
    
        $credentials = storage_path('app').'/credentials.json';
        $token = json_decode(file_get_contents(storage_path('app').'/token.json'), true);

        $client = new Google_Client();
        $client->setApplicationName('G Suite Directory API');
        $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY);
        $client->setAuthConfig($credentials);

        // set the access token, this is the tough part
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {

            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                Storage::delete('token.json');
                Storage::put('token.json', json_encode($client->getAccessToken()));
            } else {
                dd('NEED TO CREATE A NEW TOKEN');
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);
            }

        }

        return $client;

    }

}
