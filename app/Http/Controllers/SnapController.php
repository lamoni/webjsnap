<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lamoni\JSnapCommander\JSnapCommander;

class SnapController extends Controller
{

    public function snapshot(JSnapCommander $jsnap)
    {

        if (\Request::has('snapHostname')) {

            $snapshot = $jsnap->snapShot(\Request::get('snapHostname'));

            if ($snapshot['error'] === 0) {

                return ['error' => 0, 'html' => 'Snapshot successful', 'snapID' => $snapshot['snapID']];

            } else {
                return ['error' => 1, 'html' => $snapshot['html']];
            }
        }

        return ['error' => 1, 'html' => 'Must designate a hostname to snapshot'];

    }

}