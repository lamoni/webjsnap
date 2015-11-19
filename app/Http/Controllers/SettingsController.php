<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lamoni\JSnapCommander\JSnapCommander;

class SettingsController extends Controller
{

    public function delete(JSnapCommander $jsnap)
    {
        if (\Request::has('deleteHostname') && \Request::has('deleteTime')) {

            $result = $jsnap->deleteSnapshot(\Request::get('deleteHostname'), \Request::get('deleteTime'));

            dd($result);
            return ['error' => 0, 'result' => $result];

        }

        return ['error' => 1, 'html' => 'Must designate a hostname and a snap time to delete'];
    }

    public function save()
    {
        $settings = \Request::except('_token');

        $final = [];
        foreach ($settings as $settingsName => $settingsValue) {

            $split = explode('_', $settingsName);

            $final[$split[0]][$split[1]] = $settingsValue;

        }

        file_put_contents('/var/www/storage/jsnap/config.json', json_encode($final));

        return redirect('/settings');
    }

}