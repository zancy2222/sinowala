<?php

return [
    'code' => 'Error :code',
    403 => [
        'title' => 'Hindi ka puwede rito.',
        'body' => 'Wala kang access sa page na ito. Kung sa tingin mo ay dapat meron, mag-log in gamit ang tamang account o tanungin ang admin ng kumpanya mo.',
    ],
    404 => [
        'title' => 'Wala rito ang hinahanap mo.',
        'body' => 'Baka mali ang link, inalis na ang page, o naka-off na ang share link. Pakicheck ang address, o bumalik sa home.',
    ],
    419 => [
        'title' => 'Nag-expire na ang session mo.',
        'body' => 'Matagal nang bukas ang page kaya nag-expire ang form para sa seguridad. I-refresh ang page at subukan ulit — hindi nawala ang account mo.',
        'action' => 'Bumalik at subukan ulit',
    ],
    429 => [
        'title' => 'Dahan-dahan lang.',
        'body' => 'Masyadong maraming request sa maikling oras. Maghintay ng isang minuto, tapos subukan ulit.',
    ],
    500 => [
        'title' => 'May nasira sa amin.',
        'body' => 'Hindi mo ito kasalanan. Naitala na ang problema at aayusin namin. Subukan ulit pagkalipas ng ilang minuto.',
    ],
    503 => [
        'title' => 'Saglit lang, may maintenance.',
        'body' => 'Inaayos namin ang SinoWala ngayon. Babalik ito sa loob ng ilang minuto. Ligtas ang lahat ng data mo.',
    ],
];
