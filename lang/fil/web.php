<?php

/*
| Public website copy (Taglish). No invented customers, quotes or numbers.
*/

return [
    'tagline' => 'Sino\'ng wala ngayon? Kalendaryo ng team.',
    'meta_default' => 'Libreng availability calendar para sa mga indibidwal at maliliit na negosyo sa Pilipinas. Markahan ang araw na wala ka; makikita ng boss kung sino ang andito.',

    'nav' => [
        'label' => 'Website',
        'home_label' => 'SinoWala, home',
        'items' => [
            ['label' => 'Paano gumagana', 'href' => '#paano'],
            ['label' => 'Para sa kumpanya', 'href' => '#kumpanya'],
            ['label' => 'Para sa sarili', 'href' => '#sarili'],
            ['label' => 'FAQ', 'href' => 'faq'],
        ],
        'login' => 'Mag-log in',
        'signup' => 'Mag-sign up',
        'open_app' => 'Buksan ang app',
    ],

    'footer' => [
        'description' => 'Kalendaryo ng mga araw na wala ka — para sa sarili mo, o para sa buong team. Gawa para sa maliliit na negosyo sa Pilipinas.',
        'site' => 'SinoWala',
        'legal' => 'Legal',
        'operated_by' => 'Pinapatakbo ng :business',
        'links' => [
            'about' => 'Tungkol sa amin',
            'faq' => 'FAQ',
            'help' => 'Paano gamitin',
            'contact' => 'Contact',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Use',
            'cookies' => 'Cookie Policy',
            'privacy-request' => 'Data Privacy Request',
            'accessibility' => 'Accessibility',
            'cookie_settings' => 'Cookie settings',
        ],
    ],

    'cookies_banner' => [
        'title' => 'Cookies sa SinoWala',
        'body' => 'Kailangan lang namin ang mga cookie para gumana ang log in at seguridad. Kung papayag ka, puwede rin kaming gumamit ng privacy-friendly na analytics. Ikaw ang bahala.',
        'policy' => 'Basahin ang Cookie Policy',
        'accept_all' => 'Tanggapin lahat',
        'necessary' => 'Kailangan lang',
        'settings' => 'Settings',
        'dialog_title' => 'Cookie settings',
        'dialog_body' => 'Piliin kung anong cookies ang papayagan mo. Puwede mo itong baguhin anumang oras mula sa footer.',
        'necessary_title' => 'Strictly necessary',
        'necessary_body' => 'Session, seguridad (CSRF), "tandaan ako", at ang pinili mo rito. Kailangan para gumana ang site.',
        'always_on' => 'Laging naka-on',
        'analytics_title' => 'Analytics',
        'analytics_body' => 'Bilang ng bisita at mga page na binubuksan, walang personal na impormasyon. Naka-off hanggang pumayag ka.',
        'analytics_none' => 'Walang analytics na naka-install ngayon. Kung magkakaroon, naka-off ito hanggang pumayag ka.',
        'save' => 'I-save ang pinili ko',
        'saved' => 'Na-save ang cookie settings mo.',
    ],

    'home' => [
        'title' => 'Sino\'ng wala ngayon?',
        'meta' => 'Markahan ang mga araw na wala ka. Makikita agad ng boss kung sino ang andito. Libre, Taglish, at hindi namin tinatanong kung bakit.',
        'hero_title' => 'Sino\'ng wala ngayon?',
        'hero_body' => 'Isang kalendaryo para sa team mo. Minamarkahan ng bawat isa ang araw na wala sila, at sa isang tingin, alam ng boss kung sino ang andito.',
        'hero_cta' => 'Gumawa ng libreng account',
        'hero_secondary' => 'Paano gumagana',
        'hero_note' => 'Libre. Walang credit card. Para sa sarili mo o para sa buong team.',

        // The 3D month in the hero: the team's Wala days stand up as orange blocks.
        'board' => [
            'label' => 'Halimbawa: ang Nobyembre ng Kapihan sa Kanto',
            'caption' => 'Halimbawa · Kapihan sa Kanto',
            'hint' => 'Pindutin ang isang araw para markahang Wala ka.',
            'you' => 'Ikaw',
            'present' => ':day :date: andito ang :present sa :total',
            'day_label' => ':day, :date Nobyembre',
            'day_wala' => 'Wala si :names',
            'day_free' => 'Andito ang lahat',
            'day_mine' => 'Wala ka rin',
            'day_today' => 'ngayon',
            'mine_count' => '{0} Wala ka: wala pang araw|{1} Wala ka: 1 araw|[2,*] Wala ka: :count araw',
            'people' => ['Ana', 'Ben', 'Carlo', 'Dina'],
        ],

        'hero_cards' => [
            ['icon' => 'user', 'href' => '#sarili', 'title' => 'Para sa sarili mo', 'body' => 'Markahan ang lakad mo, buong araw o AM/PM lang. Hindi mo kailangan ng kumpanya.'],
            ['icon' => 'users', 'href' => '#kumpanya', 'title' => 'Para sa kumpanya', 'body' => 'Isang tingin sa "Ngayon": sino ang wala, ilan ang andito, sino ang susunod.'],
            ['icon' => 'lock', 'href' => '#privacy', 'title' => 'Walang tanong kung bakit', 'body' => 'Available o Wala lang. Ang private note mo, ikaw lang ang nakakabasa.'],
        ],

        'page_nav' => [
            'label' => 'Sa page na ito',
            'items' => [
                ['href' => '#top', 'label' => 'Home'],
                ['href' => '#paano', 'label' => 'Paano gumagana'],
                ['href' => '#sarili', 'label' => 'Para sa sarili'],
                ['href' => '#kumpanya', 'label' => 'Para sa kumpanya'],
                ['href' => '#privacy', 'label' => 'Privacy'],
                ['href' => '#faq', 'label' => 'FAQ'],
            ],
        ],

        'promo' => [
            'label' => 'Simulan ang SinoWala',
            'title' => 'Libre para sa buong team.',
            'body' => 'Gumawa ng account sa loob ng 2 minuto.',
            'cta' => 'Gumawa ng account',
            'close' => 'Isara ang paalalang ito',
        ],

        'demo' => [
            'label' => 'Halimbawa ng team calendar',
            'company' => 'Halimbawa: Kapihan sa Kanto',
            'week' => 'Linggong ito',
            'you' => 'Ikaw',
            'hint' => 'Subukan: pindutin ang isang araw sa row mo para markahang Wala.',
            'present' => 'Andito sa :day',
            'of' => 'sa',
            'people' => ['Ana Santos', 'Ben Cruz', 'Carlo Reyes', 'Dina Lopez'],
            'days' => ['Lunes', 'Martes', 'Miyerkules', 'Huwebes', 'Biyernes', 'Sabado'],
            'days_short' => ['Lun', 'Mar', 'Miy', 'Huw', 'Biy', 'Sab'],
            'mark' => 'Markahan ang :day bilang Wala',
        ],

        'how_title' => 'Paano gumagana',
        'how_body' => 'Isang linggo sa isang maliit na team. Walang form na pipirmahan, walang approval na hihintayin.',
        'how_slip_title' => 'Ang linggo ng isang team',
        'how' => [
            ['when' => 'Lunes', 'rows' => [
                ['what' => 'Gumawa ng account si Maria, ang may-ari, at pinili ang "Para sa kumpanya ko"', 'time' => '8:00 AM'],
                ['what' => 'Na-approve ang kumpanya; lumabas ang invite link at 6-character code', 'time' => 'sa araw ding iyon'],
                ['what' => 'Pinadala ni Maria ang link sa group chat ng team', 'time' => '1 minuto'],
            ]],
            ['when' => 'Martes', 'rows' => [
                ['what' => 'Sumali ang apat na staff gamit ang link', 'time' => 'tig-2 minuto'],
                ['what' => 'Nag-file si Ana ng lakad sa Biyernes, buong araw', 'time' => '3 pindot'],
                ['what' => 'Nag-file si Ben ng kalahating araw sa Huwebes: AM lang', 'time' => '3 pindot'],
            ]],
            ['when' => 'Biyernes', 'rows' => [
                ['what' => 'Binuksan ni Maria ang "Ngayon" pagdating sa tindahan', 'time' => '7:55 AM'],
            ]],
        ],
        'how_result' => 'Alam na niya: Wala si Ana. Andito: 4 sa 5.',

        'personal_title' => 'Para sa sarili mo',
        'personal_body' => 'Hindi mo kailangan ng kumpanya. Gamitin ang SinoWala para malaman mo — at ng pamilya o kliyente mo — kung kailan ka hindi puwede.',
        'personal' => [
            ['title' => 'Isang araw o isang buong linggo', 'body' => 'Pumili ng petsa, o ng simula at dulo. Puwedeng laktawan ang Sabado at Linggo kapag mahaba ang bakasyon.'],
            ['title' => 'Kalahating araw', 'body' => 'May check-up ka sa umaga? Markahan ang AM lang. Makikita ng team na babalik ka sa hapon.'],
            ['title' => 'Private note na ikaw lang ang nakakakita', 'body' => 'Isulat kung para saan ang lakad — "Renew ng passport", "Dentist". Naka-encrypt ito, at hindi ito makikita ng boss, ng team, o namin.'],
            ['title' => 'Share link kapag kailangan', 'body' => 'I-on ang public link para makita ng iba kung Available o Wala ka sa susunod na 3 buwan. Walang notes, walang email. I-off anumang oras.'],
        ],
        'personal_sample' => 'Halimbawa ng sariling kalendaryo',
        'personal_month' => 'Nobyembre',
        'personal_note' => 'Dentist sa Cubao',

        'company_title' => 'Para sa kumpanya mo',
        'company_body' => 'Para sa boss na ayaw nang magtanong sa group chat tuwing umaga. Tatlong screen lang ang kailangan mo.',
        'company_views_label' => 'Mga screen para sa kumpanya',
        'company_views' => [
            'ngayon' => ['name' => 'Ngayon', 'body' => 'Pagbukas mo pa lang: sino ang wala ngayon, ilan ang andito, at sino ang susunod na may lakad.'],
            'kalendaryo' => ['name' => 'Team calendar', 'body' => 'Buwan o linggo, nagsisimula sa Lunes. Orange ang araw na may wala, at nakasulat ang "Wala" — hindi lang kulay.'],
            'team' => ['name' => 'Team page', 'body' => 'Invite link at code, roles, at pag-alis ng miyembro. Puwede kang gumawa ng bagong code kapag kailangan.'],
        ],
        'company_points' => [
            'Libre para sa buong team, kahit ilan kayo.',
            'Isang kumpanya lang bawat tao. Kapag umalis sila, sa kanila pa rin ang kalendaryo nila.',
            'Walang leave types, walang approval, walang dahilan na hinihingi. Andito o wala lang.',
        ],

        'privacy_title' => 'Hindi namin tinatanong kung bakit ka wala.',
        'privacy_body' => 'Sadyang dalawa lang ang status sa SinoWala: Available o Wala. Walang "sick leave" o "vacation leave" na makikita ng iba, dahil hindi kailangan ng team malaman kung bakit — kailangan lang nilang malaman kung andito ka.',
        'privacy_table_caption' => 'Sino ang nakakakita ng ano',
        'privacy_cols' => ['Sino', 'Mga araw na Wala', 'AM / PM', 'Private note'],
        'privacy_rows' => [
            ['Ikaw', 'Oo', 'Oo', 'Oo'],
            ['Boss at team (active na kumpanya)', 'Oo, kasama ang pangalan mo', 'Oo', 'Hindi kailanman'],
            ['SinoWala (Super Admin)', 'Bilang lang, walang detalye', 'Hindi', 'Hindi kailanman'],
            ['Share link (kung in-on mo)', 'Oo, 3 buwan', 'Oo', 'Hindi kailanman'],
        ],
        'privacy_more' => 'Basahin ang Privacy Policy',

        'faq_title' => 'Mga madalas itanong',
        'faq_more' => 'Lahat ng tanong',

        'install_title' => 'Nasa home screen mo, parang app',
        'install_body' => 'I-install ang SinoWala sa phone para isang pindot lang ang pag-check kung sino ang wala.',
        'cta_title' => 'Simulan na',
        'cta_body' => 'Gagawa ka ng account, iva-verify ang email, at handa ka na. Para sa kumpanya, bubukas ang team calendar kapag na-approve na namin.',
        'cta_card_title' => 'Para saan mo gagamitin?',
        'cta_options' => [
            'personal' => ['label' => 'Para sa sarili ko', 'body' => 'Magagamit agad'],
            'company' => ['label' => 'Para sa kumpanya ko', 'body' => 'Ikaw ang admin'],
            'join' => ['label' => 'Sumali sa kumpanya', 'body' => 'May code o link ka'],
        ],
        'cta_button' => 'Ituloy sa sign-up',
    ],

    'faq' => [
        'title' => 'FAQ',
        'heading' => 'Mga madalas itanong',
        'meta' => 'Mga sagot tungkol sa SinoWala: presyo, privacy, kumpanya, half-day, share link, at pag-delete ng account.',
        'intro' => 'Hindi mo nakita ang tanong mo?',
        'contact' => 'Magpadala ng mensahe',
        'items' => [
            ['q' => 'Magkano ang SinoWala?', 'a' => 'Libre ito para sa personal na gamit at para sa mga kumpanya. Walang credit card na hinihingi sa sign-up.'],
            ['q' => 'Makikita ba ng boss kung bakit ako wala?', 'a' => 'Hindi. Ang makikita lang ng boss at ng team ay ang pangalan mo, ang araw, at kung buong araw, AM, o PM. Ang private note mo ay ikaw lang ang nakakakita.'],
            ['q' => 'Kailangan bang i-approve ng boss ang lakad ko?', 'a' => 'Hindi. Walang approval flow sa SinoWala. Kapag nag-file ka, makikita agad ng team. Kung may patakaran ang kumpanya ninyo tungkol sa leave, sundin pa rin iyon nang hiwalay.'],
            ['q' => 'Bakit hinihintay pa ang approval ng kumpanya ko?', 'a' => 'Tinitingnan muna namin ang bawat bagong kumpanya para maiwasan ang spam at pekeng account. Habang naghihintay, magagamit mo na ang sarili mong kalendaryo.'],
            ['q' => 'Puwede ba akong sumali sa dalawang kumpanya?', 'a' => 'Isang kumpanya lang bawat account. Kapag lumipat ka ng trabaho, umalis sa luma mula sa Settings at sumali sa bago — sasama ang buong kalendaryo mo.'],
            ['q' => 'Ano ang mangyayari sa kalendaryo ko kapag umalis ako sa kumpanya?', 'a' => 'Sa iyo pa rin ito. Ang mga Wala days at notes ay nakakabit sa account mo, hindi sa kumpanya.'],
            ['q' => 'Paano kung kalahating araw lang ako wala?', 'a' => 'Piliin ang "Umaga lang (AM)" o "Hapon lang (PM)" kapag isang araw ang ifa-file mo. Makikita ng team ang "Wala AM" o "Wala PM".'],
            ['q' => 'Ano ang share link?', 'a' => 'Isang public na page na nagpapakita lang kung Available o Wala ka sa susunod na 3 buwan. Naka-off ito hanggang i-on mo, at puwede mo itong i-off o palitan anumang oras.'],
            ['q' => 'Paano ko ida-download o ide-delete ang data ko?', 'a' => 'Sa Settings, may "I-download ang data ko" (JSON at CSV) at "I-delete ang account ko". Permanente ang pag-delete.'],
            ['q' => 'Gumagana ba ito sa phone?', 'a' => 'Oo. Gumagana ito sa kahit anong browser sa phone, tablet o computer. Puwede mo rin itong i-install sa home screen ng iPhone o Android para bumukas na parang app, nang hindi dumadaan sa App Store o Play Store.'],
            ['q' => 'Puwede ko bang gamitin ito gamit lang ang keyboard o screen reader?', 'a' => 'Oo. Gumagana ang buong app gamit ang keyboard, at may label ang bawat araw sa kalendaryo para sa screen readers. Basahin ang Accessibility Statement para sa detalye.'],
            ['q' => 'Saan naka-store ang data?', 'a' => 'Sa server na pinapatakbo ng may-ari ng SinoWala. Naka-encrypt ang private notes. Nasa Privacy Policy ang buong detalye, kasama kung gaano katagal namin itinatago ang bawat uri ng data.'],
        ],
    ],

    'about' => [
        'title' => 'Tungkol sa SinoWala',
        'meta' => 'Bakit ginawa ang SinoWala: isang simpleng kalendaryo ng kung sino ang andito at wala, para sa maliliit na team sa Pilipinas.',
        'lead' => 'Ginawa ang SinoWala para sa isang tanong na paulit-ulit sa maliliit na negosyo tuwing umaga: "Sino\'ng wala ngayon?"',
        'sections' => [
            ['title' => 'Ang problema', 'body' => [
                'Sa maliliit na team — kapihan, salon, maliit na opisina, repair shop — madalas sa group chat lang nalalaman kung sino ang hindi papasok. Natatabunan ang mensahe, nakakalimutan, at nagugulat ang lahat pagdating ng araw.',
                'Ang mga HR system naman ay ginawa para sa malalaking kumpanya: leave types, approval, payroll. Masyadong mabigat para sa team na lima o sampu lang.',
            ]],
            ['title' => 'Ang ginawa namin', 'body' => [
                'Isang kalendaryo na may dalawang status lang: Available at Wala. Bawat tao ang nagmamarka ng sarili niyang araw. Ang boss, isang tingin lang sa "Ngayon", alam na kung ilan ang andito.',
                'Puwede rin itong gamitin nang mag-isa, para sa sarili mong mga lakad, kahit wala kang kumpanya.',
            ]],
            ['title' => 'Ang paniniwala namin', 'body' => [
                'Hindi kailangan malaman ng team kung bakit ka wala. Kaya hindi namin ito tinatanong, at kung magsusulat ka ng note, ikaw lang ang makakabasa nito.',
                'Simple, libre, at nasa wikang ginagamit natin araw-araw.',
            ]],
        ],
        'operator' => 'Ang SinoWala ay pinapatakbo ng :business, :address.',
        'cta' => 'Gumawa ng account',
    ],

    'help' => [
        'title' => 'Paano gamitin',
        'meta' => 'Gabay sa SinoWala: pag-file ng lakad, team calendar, pag-imbita, share link, keyboard shortcuts, at seguridad ng account.',
        'lead' => 'Lahat ng kailangan mong malaman para magamit ang SinoWala, mula sign-up hanggang pag-delete ng account.',
        'toc' => 'Sa page na ito',
        'sections' => [
            ['id' => 'simula', 'title' => 'Pagsisimula', 'items' => [
                'Pumunta sa Mag-sign up at piliin kung para saan mo ito gagamitin: para sa sarili mo, para sa kumpanya mo, o para sumali sa kumpanya.',
                'I-verify ang email mo gamit ang link na ipapadala namin. Tingnan ang Spam folder kung hindi mo makita.',
                'Kung gumawa ka ng kumpanya, hihintayin pa ang approval namin bago bumukas ang team calendar. Magagamit mo na agad ang sarili mong kalendaryo.',
            ]],
            ['id' => 'lakad', 'title' => 'Pag-file ng lakad', 'items' => [
                'Pindutin ang "Mag-file ng lakad" (o ang N sa keyboard).',
                'Piliin ang "Mula" at "Hanggang". Para sa isang araw, pareho lang ang dalawa.',
                'Para sa isang araw, puwede mong piliin ang buong araw, AM, o PM.',
                'Opsyonal: magsulat ng private note. Ikaw lang ang makakakita nito.',
                'Para mag-edit o mag-delete, pindutin ang araw sa kalendaryo mo, tapos "I-edit".',
            ]],
            ['id' => 'team', 'title' => 'Team calendar', 'items' => [
                'Sa "Ngayon", piliin ang "Team" para makita kung sino ang wala ngayon at ilan ang andito.',
                'Sa Team calendar, lumipat sa Buwan o Linggo. Sa Linggo view, may row ang bawat tao at may bilang ng andito sa ibaba.',
                'Pindutin ang isang araw para makita kung sino ang wala. Gamitin ang "Hanapin ang tao" para sa malalaking team.',
            ]],
            ['id' => 'imbita', 'title' => 'Pag-imbita at roles (para sa admin)', 'items' => [
                'Sa Team page, kopyahin ang invite link o ang 6-character code at ibigay sa team.',
                'Kung may nakakuha ng code na hindi dapat, pindutin ang "Gumawa ng bagong link at code". Hihinto agad ang luma.',
                'Puwede kang mag-promote ng ibang admin. Kailangan laging may kahit isang admin ang kumpanya.',
                'Ang pag-alis ng miyembro ay hindi nagbubura ng personal niyang kalendaryo.',
            ]],
            ['id' => 'share', 'title' => 'Share link', 'items' => [
                'Sa Settings → Share link, i-on ito para makakuha ng public na link.',
                'Available o Wala lang ang makikita sa susunod na 3 buwan. Walang notes, email, o pangalan ng kumpanya.',
                'I-off o gumawa ng bagong link anumang oras. Hindi na gagana ang luma.',
            ]],
            ['id' => 'keyboard', 'title' => 'Keyboard shortcuts', 'items' => [
                'N: mag-file ng lakad · T: ngayon · ← / →: nakaraan o susunod · M: month view · W: week view · /: hanapin ang tao · ?: listahan ng shortcuts.',
                'Sa loob ng kalendaryo: arrow keys para lumipat ng araw, Home/End para sa simula at dulo ng linggo, Page Up/Page Down para sa buwan, Enter para buksan ang araw.',
                'Puwedeng i-off ang single-key shortcuts sa Settings kung nakakaabala sa screen reader o voice control.',
            ]],
            ['id' => 'seguridad', 'title' => 'Seguridad ng account', 'items' => [
                'I-on ang two-factor authentication sa Settings. Itago ang recovery codes sa ligtas na lugar.',
                'Sa Browser sessions, makikita mo ang mga device na naka-log in at puwede mong i-log out ang iba.',
                'Hindi kailanman hihingin ng SinoWala ang password mo sa email o chat.',
            ]],
            ['id' => 'data', 'title' => 'Ang data mo', 'items' => [
                'I-download ang lahat ng data mo mula sa Settings → Ang data mo.',
                'I-delete ang account mo mula sa Settings. Kung ikaw lang ang admin ng kumpanya, mag-promote muna ng iba o i-delete ang kumpanya.',
                'Para sa ibang request sa ilalim ng Data Privacy Act, gamitin ang Data Privacy Request form.',
            ]],
        ],
    ],

    'contact' => [
        'title' => 'Contact',
        'heading' => 'Magpadala ng mensahe',
        'meta' => 'Makipag-ugnayan sa SinoWala para sa tanong, problema, o suggestion.',
        'lead' => 'May tanong, problema sa account, o suggestion? Sasagot kami sa email na ilalagay mo, kadalasan sa loob ng 2 araw na may pasok.',
        'privacy_hint' => 'Para sa request tungkol sa personal data mo (access, correction, deletion, export), gamitin ang Data Privacy Request form para ma-log ito nang tama.',
        'name' => 'Pangalan',
        'email' => 'Email',
        'message' => 'Mensahe',
        'message_help' => 'Huwag ilagay ang password mo o anumang sensitibong impormasyon.',
        'submit' => 'Ipadala',
        'sent_title' => 'Natanggap na namin ang mensahe mo.',
        'sent_body' => 'Sasagot kami sa email na inilagay mo. Salamat!',
        'honeypot' => 'Huwag sagutan ito',
        'other_ways' => 'Iba pang paraan',
        'email_us' => 'Email',
        'attributes' => ['name' => 'pangalan', 'email' => 'email', 'message' => 'mensahe'],
    ],

    'privacy_request' => [
        'title' => 'Data Privacy Request',
        'meta' => 'Gamitin ang iyong mga karapatan sa ilalim ng Data Privacy Act of 2012: access, correction, deletion, data portability, at pagtutol.',
        'lead' => 'Sa ilalim ng Data Privacy Act of 2012 (RA 10173), may karapatan kang makita, itama, ipabura, at makuha ang kopya ng personal data mo. Ilagay ang request mo rito at sasagutin namin sa loob ng 15 araw.',
        'self_service' => 'Mas mabilis: kung naka-log in ka, puwede mong i-download o i-delete ang data mo agad mula sa Settings.',
        'self_service_link' => 'Pumunta sa Settings',
        'name' => 'Buong pangalan',
        'email' => 'Email ng account mo',
        'email_help' => 'Gamitin ang email na nasa SinoWala account mo, para ma-verify namin na ikaw iyon.',
        'type' => 'Uri ng request',
        'details' => 'Detalye',
        'details_help' => 'Halimbawa: anong data ang gusto mong itama, at ano ang tamang value.',
        'submit' => 'Ipadala ang request',
        'sent_title' => 'Natanggap at na-log na ang request mo.',
        'sent_body' => 'Kokontakin ka namin sa email para i-verify ang pagkakakilanlan mo bago namin gawin ang request.',
        'types' => [
            'access' => 'Access — makita ang data ko',
            'correction' => 'Correction — itama ang data ko',
            'deletion' => 'Deletion — ipabura ang data ko',
            'export' => 'Data portability — kopya ng data ko',
            'objection' => 'Objection — tumutol sa pagproseso',
        ],
        'attributes' => ['name' => 'buong pangalan', 'email' => 'email', 'type' => 'uri ng request', 'details' => 'detalye'],
    ],

    'share' => [
        'title' => 'Availability ni :name',
        'meta' => 'Kung kailan Available o Wala, sa susunod na 3 buwan.',
        'subtitle' => 'Available o Wala, :range.',
        'footnote' => 'Ipinapakita lang ng page na ito kung Available o Wala ang tao. Walang ibang impormasyon.',
        'what' => 'Ano ang SinoWala?',
    ],

    'legal' => [
        'updated' => 'Huling na-update',
        'version' => 'Bersyon',
        'toc' => 'Nilalaman',
    ],
];
