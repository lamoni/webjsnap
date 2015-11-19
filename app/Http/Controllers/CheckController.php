<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lamoni\JSnapCommander\JSnapCommander;

class CheckController extends Controller
{

    public function check(JSnapCommander $jsnap)
    {
        if (\Request::has('checkHostname')) {

            $result = $jsnap->snapCheck(\Request::get('checkHostname'));

            return ['error' => 0, 'result' => $result];

        }

        return ['error' => 1, 'html' => 'Must designate a hostname, presnap, and postsnap'];
    }
}