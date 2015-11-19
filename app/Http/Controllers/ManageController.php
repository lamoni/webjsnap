<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lamoni\JSnapCommander\JSnapCommander;

class ManageController extends Controller
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
}