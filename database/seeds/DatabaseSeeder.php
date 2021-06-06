<?php

use Illuminate\Database\Seeder;
use App\User;
use App\AppConfig;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate( 
            [
                'password'      => '123456aA',
                'name'          => 'admin',
                'email'         => 'admin@admin'
            ]
        );

        AppConfig::firstOrCreate( 
            [
                'native_ad_id'      => 'ca-app-pub-3940256099942544~3347511713',
                'interstitial_ad_id'          => 'ca-app-pub-3940256099942544/1033173712',
                'banner_ad_id'         => 'ca-app-pub-3940256099942544/6300978111',
                'reward_ad_id'         => 'ca-app-pub-3940256099942544/5224354917',
                'firebase_server_key'         => 'AAAArA3JlBA:APA91bEd9XoOHs6i8H3ROhsPJER_x8DKmfsm8wZGNq8Gfu_eNvXbfkrKQos0TTXZy1x8vxzYRxa-I1ifU1qMF-icfJhQBOjBvYDYOGf7l7xxpaF5UTsTRSJRfESy5ud-ic-3b_MKJMQX',
                'package_name'         => 'com.packagename.quiz_app',
                'contact_mail'         => 'saliht94@gmail.com'
            ]
        );
    }
}
