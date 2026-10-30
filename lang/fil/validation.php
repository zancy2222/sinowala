<?php

/*
| Validation messages in Taglish. Each one says how to fix the problem.
| Rules not listed here fall back to English (lang fallback).
*/

return [
    'accepted' => 'Kailangang i-check ang :attribute para magpatuloy.',
    'after' => 'Dapat pagkatapos ng :date ang :attribute.',
    'after_or_equal' => 'Dapat :date o pagkatapos nito ang :attribute.',
    'alpha_num' => 'Letra at numero lang ang puwede sa :attribute.',
    'array' => 'Hindi valid ang :attribute.',
    'before' => 'Dapat bago ang :date ang :attribute.',
    'before_or_equal' => 'Dapat :date o mas maaga ang :attribute.',
    'boolean' => 'Oo o hindi lang ang puwede sa :attribute.',
    'confirmed' => 'Hindi magkapareho ang dalawang password. I-type ulit nang pareho.',
    'current_password' => 'Mali ang password. I-type ang kasalukuyang password mo.',
    'date' => 'Hindi valid na petsa ang :attribute.',
    'date_format' => 'Pumili ng petsa para sa :attribute.',
    'email' => 'Hindi valid na email ang :attribute. Halimbawa: juan@gmail.com',
    'in' => 'Pumili ng isa sa mga opsyon para sa :attribute.',
    'integer' => 'Numero lang ang puwede sa :attribute.',
    'max' => [
        'numeric' => 'Hindi puwedeng lumampas sa :max ang :attribute.',
        'string' => 'Hanggang :max characters lang ang :attribute.',
        'array' => 'Hanggang :max lang ang :attribute.',
        'file' => 'Hanggang :max KB lang ang :attribute.',
    ],
    'min' => [
        'numeric' => 'Dapat hindi bababa sa :min ang :attribute.',
        'string' => 'Dapat hindi bababa sa :min characters ang :attribute.',
        'array' => 'Dapat may kahit :min ang :attribute.',
        'file' => 'Dapat hindi bababa sa :min KB ang :attribute.',
    ],
    'numeric' => 'Numero lang ang puwede sa :attribute.',
    'password' => [
        'letters' => 'Dapat may kahit isang letra ang :attribute.',
        'mixed' => 'Dapat may malaki at maliit na titik ang :attribute.',
        'numbers' => 'Dapat may kahit isang numero ang :attribute.',
        'symbols' => 'Dapat may kahit isang simbolo ang :attribute.',
        'uncompromised' => 'Lumabas na ang password na ito sa isang kilalang data breach. Pumili ng iba.',
    ],
    'prohibited' => 'Hindi puwedeng lagyan ang :attribute.',
    'required' => 'Kailangan ang :attribute.',
    'required_if' => 'Kailangan ang :attribute.',
    'required_unless' => 'Kailangan ang :attribute.',
    'size' => [
        'string' => 'Dapat eksaktong :size characters ang :attribute.',
        'numeric' => 'Dapat :size ang :attribute.',
    ],
    'string' => 'Hindi valid ang :attribute.',
    'unique' => 'May account na gamit ang :attribute na ito. Mag-log in, o gumamit ng ibang email.',
    'uuid' => 'Hindi valid ang :attribute.',

    'custom' => [],

    'attributes' => [
        'name' => 'pangalan',
        'email' => 'email',
        'password' => 'password',
        'current_password' => 'kasalukuyang password',
        'password_confirmation' => 'ulit ng password',
        'company_name' => 'pangalan ng kumpanya',
        'code' => 'code',
        'terms' => 'Terms of Use at Privacy Policy',
        'message' => 'mensahe',
        'type' => 'uri ng request',
        'details' => 'detalye',
        'confirm_name' => 'pangalan ng kumpanya',
        'recovery_code' => 'recovery code',
    ],
];
