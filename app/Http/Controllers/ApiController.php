<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use App\Report;
use App\AppConfig;

class ApiController extends Controller
{
    //

	public function getAdIds(Request $request) {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("Hello from Terminal");
		$app_config = AppConfig::first();
		if(is_null($app_config)) {
    		return response('Status code: 500', 500);
    	}
        $out->writeln("Hello from Terminal");
		if($request->header('package_name') == $app_config->package_name) {
			$response = array(
				'interstitial_ad_id' => $app_config->interstitial_ad_id,
				'banner_ad_id' => $app_config->banner_ad_id,
				'reward_ad_id' => $app_config->reward_ad_id,
                'contact_mail' => $app_config->contact_mail,
			);
            $out->writeln($response);

			return $response;
		}
		return response('Status code: 401. Unauthenticated.', 401);
	}

    public function getCategories(Request $request) {
    	$app_config = AppConfig::first();
    	if(is_null($app_config)) {
    		return response('Status code: 500', 500);
    	}
    	if($request->header('package_name') == $app_config->package_name) {
    		$data = Category::latest()->where('status', 'active')->get()->reverse();
    		$response = array();
    		foreach ($data as $key => $value) {
    			# code...
                $total_question =  Question::where("status", "active")->where("category_id", $value->id)->count();
                if($total_question < 3) {
                    continue;
                }
    			$response[] = array(
    				'id' => $value->id,
    				'name' => $value->name,
    				'image_url' => $value->image_url,
                    'total_question' => $total_question,
    			);
    			
    		}
    		return $response;
    	}
    	return response('Status code: 401. Unauthenticated.', 401);
    }

    public function getQuestions(Request $request) {
		$app_config = AppConfig::first();
		if(is_null($app_config)) {
    		return response('Status code: 500', 500);
    	}
		if($request->header('package_name') == $app_config->package_name) {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("Hello from Terminal");
			$data = Question::latest()
			->where('status', 'active')
			->where('category_id', $request->header('category_id'))
			->get();
			$response = array();
			foreach ($data as $key => $value) {
				# code...
				$response[] = array(
					'id' => $value->id,
					'question' => $value->question,
					'true_answer' => $value->true_answer,
					'false_answer1' => $value->false_answer1,
					'false_answer2' => $value->false_answer2,
					'false_answer3' => $value->false_answer3,
					'level' => $value->level,
                    'category_id' => $value->category_id,	
				);
			}
			
			return $response;
		}
		return response('Status code: 401. Unauthenticated.', 401);
	}

    /*public function getCategories(Request $request) {
    	$app_config = AppConfig::first();
    	if($request->header('package_name') == $app_config->package_name) {
    		$data = Category::latest()->where('status', 'active')->where('lang_code', $request->lang_code)->get();
    		$response = array();
    		foreach ($data as $key => $value) {
    			# code...
    			$response[] = array(
    				'id' => $value->id,
    				'category_name' => $value->name,
    				'image_url' => $value->image_url,
    			);
    			
    		}
    		return $response;
    	}
    	return response('Statuts code: 401. Unauthenticated.', 401);
    }*/

    public function reportFromApp(Request $request) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
    	$out->writeln("Hello from Terminal");

		$app_config = AppConfig::first();
		if(is_null($app_config)) {
    		return response('Status code: 500', 500);
    	}
		if($request->header('package_name') == $app_config->package_name) {
			$out->writeln("Hello from Terminal");
			$note = $request->note;
			$question_id = $request->question_id;
      		$out->writeln($question_id);
            $out->writeln($note);
			$form_data = array(
	            'note'        =>  $note,
	            'question_id'  =>  $question_id,
        	);
        	Report::create($form_data);
            $response = array(
                'OK' => 200,
            );
			return $response;
			
		}
		return response('Status code: 401. Unauthenticated.', 401);
	}
}
