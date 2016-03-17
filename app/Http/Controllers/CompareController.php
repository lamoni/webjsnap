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
        if (\Request::has('compareHostname')) {

	        if (\Request::has('selectPreSnap') && \Request::has('selectPostSnap')) {

		        $selectPreSnap = \Request::get('selectPreSnap');

		        $selectPostSnap = \Request::get('selectPostSnap');

	        }
	        else {
		        // No snapshots specified, so assume the two latest snapshots are preferred
		        $presnaps = $jsnap->loadSnapshotList(\Request::get('compareHostname'));

		        if (count($presnaps) < 2) {

			        return ['error' => 1, 'html' => 'Must be at least two snapshots to do a comparison'];

		        }

		        $keys = array_keys($presnaps);

		        $selectPreSnap = $keys[0];

		        $selectPostSnap = $keys[1];
	        }

            $results = $jsnap->check(\Request::get('compareHostname'), $selectPreSnap, $selectPostSnap);




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

	        if (\Request::has('format') && \Request::get('format') === 'raw') {
		        $rawOutput = "All Tests Passed Successfully";

		        if (isset($final['failedTests'])) {
			        $rawOutput = "FAILED TESTS\n\n";
			        foreach ($final['failedTests'] as $failedTestName => $names) {
				        $rawOutput .= "{$failedTestName}\n";
				        foreach ($names as $name=>$value) {
					        $value = trim($value);
					        $rawOutput .= "  {$name}\n    {$value}";
				        }
			        }
		        }

		        return $rawOutput;
	        }
            return ['error' => 0, 'result' => $final];

        }

        return ['error' => 1, 'html' => 'Must designate a hostname, presnap, and postsnap'];
    }
}
