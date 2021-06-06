<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppConfig;
use Illuminate\Support\Facades\Auth;

class AppConfigController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
    	$app_config = AppConfig::first();
        return view('appconfig')->with('app_config', $app_config);
    }

    public function update(Request $request)
    {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminalaaaaaaa");
        $request->validate([
            'native_ad_id' => 'nullable|max:255',
            'interstitial_ad_id' => 'required|max:255',
            'banner_ad_id' => 'required|max:255',
            'reward_ad_id' => 'required|max:255',
            'firebase_server_key' => 'required|max:255',
            'package_name' => 'required|max:255',
            'contact_mail' => 'required|max:255'
        ]);

        

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminalaaaaaaa");
        $form_data = array(
            'interstitial_ad_id'      =>  $request->input('interstitial_ad_id'),
            'banner_ad_id'     =>  $request->input('banner_ad_id'),
            'reward_ad_id'	=> $request->input('reward_ad_id'),
            'firebase_server_key'      =>  $request->input('firebase_server_key'),
            'package_name'    =>  $request->input('package_name'),
            'contact_mail'    =>  $request->input('contact_mail')
        );


        if(!is_null($request->input('native_ad_id'))) {
        	$out->writeln("Hello from native");
        	$form_data['native_ad_id'] = $request->input('native_ad_id');
        }
        $out->writeln($form_data);
        $app_config = AppConfig::first();
        $out->writeln($app_config);
        if(!is_null($app_config)) {
        	$out->writeln("Hello from native");
	        $app_config->update($form_data);
	        $app_config->save();
        }
        else {
        	$out->writeln("Hello from Terminalaaaaaaa");
        	AppConfig::create($form_data);
        }
        
        return redirect()->route('appconfig')->withSuccess('App Configs updated successfully.');
    }

}
