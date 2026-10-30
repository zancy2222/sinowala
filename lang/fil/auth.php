<?php

return [
    // Laravel / Fortify core messages — generic on purpose (no hint whether an email exists).
    'failed' => 'Hindi tugma ang email at password. Pakicheck at subukan ulit.',
    'password' => 'Mali ang password.',
    'throttle' => 'Masyadong maraming subok. Subukan ulit pagkalipas ng :seconds segundo.',
    'suspended' => 'Naka-suspend ang account na ito. Makipag-ugnayan sa SinoWala kung sa tingin mo ay mali ito.',
    'two_factor_invalid' => 'Hindi tama ang code. Gamitin ang pinakabagong code sa app, o isang recovery code.',

    'fields' => [
        'name' => 'Pangalan',
        'email' => 'Email',
        'password' => 'Password',
        'new_password' => 'Bagong password',
        'password_confirmation' => 'Ulitin ang password',
        'two_factor_code' => 'Authentication code',
        'recovery_code' => 'Recovery code',
    ],

    'login' => [
        'title' => 'Mag-log in',
        'meta' => 'Mag-log in sa SinoWala para makita ang kalendaryo mo at ng team mo.',
        'subtitle' => 'Bago ka pa lang?',
        'no_account' => 'Gumawa ng account',
        'remember' => 'Tandaan ako sa device na ito',
        'forgot' => 'Nakalimutan ang password?',
        'submit' => 'Mag-log in',
    ],

    'register' => [
        'title' => 'Gumawa ng account',
        'meta' => 'Libre ang SinoWala. Gumawa ng account para sa sarili mo, para sa kumpanya mo, o para sumali sa team.',
        'lead' => 'Libre ang SinoWala: isang kalendaryo ng mga araw na wala ka, para sa sarili mo o para sa buong team.',
        'subtitle' => 'May account ka na?',
        'have_account' => 'Mag-log in',
        'path_legend' => 'Para saan mo gagamitin ang SinoWala?',
        'path_personal' => 'Para sa sarili ko',
        'path_personal_desc' => 'Sarili mong kalendaryo ng mga araw na Wala ka. Magagamit agad.',
        'path_company' => 'Para sa kumpanya ko',
        'path_company_desc' => 'Ikaw ang magiging admin ng team. Bubukas ang team calendar kapag na-approve na namin ang kumpanya.',
        'path_join' => 'Sumali sa kumpanya',
        'path_join_desc' => 'May invite link o 6-character code ka mula sa boss mo.',
        'company_name' => 'Pangalan ng kumpanya',
        'company_help' => 'Gaya ng pagkakakilala sa inyo ng team — hal. "Kapihan sa Kanto".',
        'code' => 'Company code',
        'code_help' => '6 na letra o numero, hal. K7Q2MX.',
        'code_optional' => 'Hindi na kailangan kung galing ka sa invite link.',
        'invite_ready' => 'Galing ka sa invite link. Sasali ka sa kumpanya pagkatapos mag-sign up.',
        'name_help' => 'Ito ang makikita ng team mo sa team calendar.',
        'terms_prefix' => 'Nabasa ko at sumasang-ayon ako sa',
        'terms_and' => 'at sa',
        'terms' => 'Nabasa ko at sumasang-ayon ako sa Terms of Use at Privacy Policy.',
        'terms_short' => 'Terms of Use at Privacy Policy',
        'submit' => 'Gumawa ng account',
    ],

    'forgot' => [
        'title' => 'Nakalimutan ang password',
        'subtitle' => 'I-type ang email ng account mo. Kung may account dito, padadalhan ka namin ng link para gumawa ng bagong password.',
        'submit' => 'Ipadala ang reset link',
        'back' => 'Bumalik sa log in',
    ],

    'reset' => [
        'title' => 'Gumawa ng bagong password',
        'subtitle' => 'Pagkatapos nito, puwede ka nang mag-log in gamit ang bagong password.',
        'submit' => 'I-save ang bagong password',
    ],

    'verify' => [
        'title' => 'I-verify ang email mo',
        'body' => 'Nagpadala kami ng link sa :email. Buksan ang email at pindutin ang link para magamit ang SinoWala. Tingnan din ang Spam o Promotions folder.',
        'sent' => 'Naipadala na ang bagong verification link.',
        'resend' => 'Ipadala ulit ang link',
        'wrong_email' => 'Mali ang email? Mag-log out at gumawa ulit ng account gamit ang tamang email.',
    ],

    'confirm' => [
        'title' => 'Kumpirmahin ang password',
        'subtitle' => 'Para sa seguridad, i-type ulit ang password mo bago ituloy.',
        'submit' => 'Kumpirmahin',
    ],

    'two_factor' => [
        'title' => 'Two-factor authentication',
        'subtitle_code' => 'I-type ang 6-digit code mula sa authenticator app mo.',
        'subtitle_recovery' => 'I-type ang isa sa mga recovery code mo.',
        'submit' => 'Ituloy',
        'use_recovery' => 'Gumamit ng recovery code',
        'use_code' => 'Gumamit ng code mula sa app',
    ],

    // Fortify session statuses, shown as toasts in the app.
    'status' => [
        'profile-information-updated' => 'Na-save ang profile mo.',
        'password-updated' => 'Napalitan na ang password mo.',
        'two-factor-authentication-enabled' => 'Halos tapos na: i-scan ang QR code at i-confirm ang code.',
        'two-factor-authentication-confirmed' => 'Naka-on na ang two-factor authentication.',
        'two-factor-authentication-disabled' => 'Naka-off na ang two-factor authentication.',
        'recovery-codes-generated' => 'May bago ka nang recovery codes.',
        'verification-link-sent' => 'Naipadala na ang bagong verification link.',
    ],
];
