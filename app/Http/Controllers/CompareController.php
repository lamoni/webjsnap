<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lamoni\JSnapCommander\JSnapCommander;

class CompareController extends Controller
{

    public function presnaps(JSnapCommander $jsnap)
    {

        if (\Request::has('compareHostname')) {

            $presnaps = $jsnap->loadPreSnapList(\Request::get('compareHostname'));

            if (count($presnaps) >= 1) {

                end($presnaps);
                $postsnaps = $jsnap->loadPostSnapList(\Request::get('compareHostname'), key($presnaps));

                return ['error' => 0, 'presnaps' => $presnaps, 'postsnaps' => $postsnaps];

            }

            return ['error' => 1, 'html' => 'There must be at least two (2) snapshots to execute snapshot comparison'];

        }

        return ['error' => 1, 'html' => 'Unable to retriever pre-snap list'];

    }

    public function postsnaps(JSnapCommander $jsnap)
    {

        if (\Request::has('compareHostname') && \Request::has('selectPreSnap')) {
            $postsnaps = $jsnap->loadPostSnapList(\Request::get('compareHostname'), \Request::get('selectPreSnap'));

            return ['error' => 0, 'postsnaps' => $postsnaps];
        }

        return ['error' => 1, 'html' => 'Unable to retriever pre-snap list'];

    }

    public function compare(JSnapCommander $jsnap)
    {
        if (\Request::has('compareHostname') && \Request::has('selectPreSnap') && \Request::has('selectPostSnap')) {

            $results = $jsnap->check(\Request::get('compareHostname'), \Request::get('selectPreSnap'), \Request::get('selectPostSnap'));

            $final = [];

            foreach($results['passedTests'] as $passedTestName => $passedTestValue) {

                foreach ($passedTestValue as $name=>$value) {

                    if (strpos($value, 'Health Check') === false) {

                        $final['passedTests'][$passedTestName][] = $value;

                    }

                }

            }

            foreach($results['failedTests'] as $failedTestName => $failedTestValue) {

                foreach ($failedTestValue as $name=>$value) {

                    if (strpos($name, 'Health Check') === false) {

                        $final['failedTests'][$failedTestName][$name] = $value;

                    }

                }

            }

            return ['error' => 0, 'result' => $final];

        }

        return ['error' => 1, 'html' => 'Must designate a hostname, presnap, and postsnap'];
    }
}