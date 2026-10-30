<?php

/*
| Legal pages. Plain Taglish. Placeholders (:business, :address, :email, :dpo_name, :dpo_email)
| come from BUSINESS_* and DPO_* in .env — replace the [PLACEHOLDER] values there before launch.
| When you change the substance of a document, bump its version in config/sinowala.php.
|
| Paalala: hindi ito legal advice. Ipa-review sa abogado bago mag-launch.
*/

return [
    'privacy' => [
        'title' => 'Privacy Policy',
        'meta' => 'Paano kinokolekta, ginagamit, at pinoprotektahan ng SinoWala ang personal data mo, ayon sa Data Privacy Act of 2012 (RA 10173).',
        'intro' => 'Ipinapaliwanag ng Privacy Policy na ito kung anong personal data ang kinokolekta ng SinoWala, bakit, sino ang nakakakita nito, gaano katagal namin ito itinatago, at ano ang mga karapatan mo sa ilalim ng Data Privacy Act of 2012 (Republic Act No. 10173) at ng mga implementing rules nito.',
        'sections' => [
            ['title' => 'Sino kami', 'body' => [
                'Ang SinoWala ay pinapatakbo ng :business, na may address sa :address ("kami"). Kami ang personal information controller ng data na inilalagay mo sa SinoWala.',
                'Para sa anumang tanong tungkol sa privacy, mag-email sa :email o sa aming Data Protection Officer (seksyon 9).',
            ]],
            ['title' => 'Anong data ang kinokolekta namin', 'body' => [
                ['list' => [
                    'Account: pangalan, email, password (naka-hash; hindi namin ito nababasa), at kung naka-on ang two-factor authentication.',
                    'Kalendaryo: ang mga petsang minarkahan mong Wala, at kung buong araw, AM, o PM.',
                    'Private notes: ang note na isinusulat mo sa isang lakad. Naka-encrypt ito sa database.',
                    'Kumpanya: pangalan ng kumpanya, ang role mo (admin o member), at kailan ka sumali.',
                    'Pagsang-ayon: ang bersyon ng Terms of Use at Privacy Policy na tinanggap mo at kailan, at ang pinili mo sa cookie settings.',
                    'Seguridad: IP address at uri ng browser ng mga naka-log in mong session, oras ng huling log in, at mga aksyon ng admin sa audit log.',
                    'Mga mensahe: ang ipinapadala mo sa contact form o sa Data Privacy Request form.',
                ]],
                'Hindi namin tinatanong kung bakit ka wala. Walang leave types, walang dahilan, at walang sensitive personal information (gaya ng kalusugan) na hinihingi ang SinoWala. Kung magsusulat ka ng ganitong detalye sa private note, ikaw lang ang makakabasa nito.',
            ]],
            ['title' => 'Bakit namin ito ginagamit at ang legal na batayan', 'body' => [
                ['table' => [
                    'head' => ['Layunin', 'Data', 'Legal na batayan'],
                    'rows' => [
                        ['Paggawa at pagpapatakbo ng account mo', 'Account, kalendaryo, notes', 'Pagtupad sa kontrata (Terms of Use) — Sec. 12(b), RA 10173'],
                        ['Pagpapakita ng team calendar sa kumpanya mo', 'Pangalan, mga araw na Wala, AM/PM', 'Pagtupad sa kontrata, at ang pagsali mo mismo sa kumpanya'],
                        ['Seguridad, pag-iwas sa abuso, audit log', 'IP, sessions, audit log', 'Lehitimong interes — Sec. 12(f)'],
                        ['Pagsagot sa mensahe at privacy request', 'Pangalan, email, mensahe', 'Lehitimong interes at legal na obligasyon — Sec. 12(c), 12(f)'],
                        ['Analytics (kung naka-on lang)', 'Bilang ng bisita, walang personal na data', 'Pahintulot mo (consent) — Sec. 12(a)'],
                        ['Backups', 'Lahat ng nasa database', 'Lehitimong interes: maibalik ang serbisyo kapag may aberya'],
                    ],
                ]],
                'Hindi namin ibinebenta ang data mo, at hindi namin ito ginagamit para sa advertising.',
            ]],
            ['title' => 'Sino ang nakakakita ng ano', 'body' => [
                ['list' => [
                    'Ikaw: lahat ng data mo, kasama ang private notes.',
                    'Mga kasama mo sa isang active na kumpanya (boss at members): ang pangalan mo, ang mga araw na Wala ka, at kung AM o PM. Hindi kailanman ang notes mo.',
                    'Super Admin ng SinoWala: mga bilang lang (ilang user, ilang kumpanya) at ang listahan ng mga account at kumpanya para sa approval at suspension. Hindi nakikita ng Super Admin ang mga kalendaryo o notes ninuman.',
                    'Ang share link mo (kung in-on mo): kung Available o Wala ka sa susunod na 3 buwan, at ang unang pangalan mo. Walang notes, email, o pangalan ng kumpanya.',
                ]],
                'Gumagamit kami ng service providers para sa hosting, email, at (kung naka-on) analytics. Pinoproseso lang nila ang data ayon sa utos namin at may kasunduan kaming nagpoprotekta rito. Kung nasa labas ng Pilipinas ang alinman sa kanila, sinisiguro naming may katumbas na proteksyon ang data mo.',
                'Ibibigay lang namin ang data mo sa gobyerno kung may valid na utos ng korte o hinihingi ng batas.',
            ]],
            ['title' => 'Gaano katagal namin itinatago', 'body' => [
                ['table' => [
                    'head' => ['Data', 'Gaano katagal'],
                    'rows' => [
                        ['Account, kalendaryo, notes', 'Hangga\'t may account ka. Permanenteng nabubura kapag dinelete mo ang account.'],
                        ['Account na hindi na-verify ang email', '30 araw, tapos awtomatikong nabubura'],
                        ['Mga mensahe sa contact form', '1 taon'],
                        ['Audit log ng admin', '2 taon'],
                        ['Cookie consent records', '2 taon, o hanggang i-delete mo ang account'],
                        ['Natapos na privacy requests', '2 taon, bilang patunay na nasagot namin'],
                        ['Database backups', '14 na araw, tapos napapalitan'],
                    ],
                ]],
                'Kapag dinelete mo ang account mo, maaaring manatili pa ang data sa backups nang hanggang 14 na araw bago tuluyang mawala.',
            ]],
            ['title' => 'Paano namin pinoprotektahan ang data', 'body' => [
                ['list' => [
                    'HTTPS sa buong site, at secure, HttpOnly na session cookies.',
                    'Naka-hash ang passwords, at tinitingnan kung lumabas na sila sa mga kilalang data breach.',
                    'Naka-encrypt ang private notes sa database.',
                    'Two-factor authentication para sa lahat ng user, at required ito sa Super Admin.',
                    'Mahigpit na access rules: bawat request ay tinitingnan kung pinapayagan kang makita ang data.',
                    'Rate limits sa log in, sign-up, at mga form; araw-araw na backup.',
                ]],
            ]],
            ['title' => 'Ang mga karapatan mo', 'body' => [
                'Sa ilalim ng Data Privacy Act, may karapatan ka na:',
                ['list' => [
                    'Ma-inform kung paano pinoproseso ang data mo (ito ang Privacy Policy na iyon).',
                    'Ma-access ang data mo — puwede mo itong i-download anumang oras sa Settings → "I-download ang data ko".',
                    'Ipatama ang maling data — palitan ang pangalan at email sa Settings, o mag-request sa amin.',
                    'Ipabura ang data mo — sa Settings → "I-delete ang account ko", o mag-request sa amin.',
                    'Data portability — makakuha ng kopya sa JSON at CSV na format.',
                    'Tumutol sa pagproseso, at bawiin ang pahintulot mo sa analytics anumang oras sa Cookie settings.',
                    'Magreklamo sa National Privacy Commission (privacy.gov.ph) kung sa tingin mo ay nilabag ang mga karapatan mo.',
                    'Humingi ng danyos kung napinsala ka dahil sa maling pagproseso ng data mo.',
                ]],
                'Gamitin ang Data Privacy Request form (:request_url). Sasagutin namin sa loob ng 15 araw. Maaari naming hingin na i-verify mo muna ang pagkakakilanlan mo.',
            ]],
            ['title' => 'Kapag may data breach', 'body' => [
                'Kung may personal data breach na maaaring makapinsala sa iyo, aabisuhan namin ang National Privacy Commission at ang mga apektadong user sa loob ng 72 oras mula nang malaman namin ito, ayon sa NPC Circular 16-03.',
                'Sasabihin namin kung ano ang nangyari, anong data ang apektado, ano na ang ginawa namin, at ano ang puwede mong gawin para protektahan ang sarili mo.',
            ]],
            ['title' => 'Data Protection Officer', 'body' => [
                'Data Protection Officer: :dpo_name',
                'Email: :dpo_email',
                'Address: :address',
            ]],
            ['title' => 'Mga bata', 'body' => [
                'Para sa edad 18 pataas ang SinoWala. Hindi kami sadyang nangongolekta ng data ng mga menor de edad.',
            ]],
            ['title' => 'Mga pagbabago sa policy na ito', 'body' => [
                'Kapag binago namin ang Privacy Policy, ia-update namin ang petsa at bersyon sa itaas. Kung malaki ang pagbabago, hihilingin naming tanggapin mo ulit ito sa susunod mong pag-log in.',
            ]],
        ],
    ],

    'terms' => [
        'title' => 'Terms of Use',
        'meta' => 'Ang mga patakaran sa paggamit ng SinoWala: account, tamang paggamit, suspension, limitasyon ng pananagutan, at batas ng Pilipinas.',
        'intro' => 'Sa paggawa ng account o paggamit ng SinoWala, sumasang-ayon ka sa mga patakarang ito. Basahin nang mabuti. Kung hindi ka sang-ayon, huwag gamitin ang serbisyo.',
        'sections' => [
            ['title' => 'Ang serbisyo', 'body' => [
                'Ang SinoWala ay isang online na kalendaryo kung saan minamarkahan mo ang mga araw na hindi ka available ("Wala"). Puwede mo itong gamitin nang mag-isa, o bilang miyembro ng isang kumpanya para makita ng team kung sino ang andito.',
                'Pinapatakbo ito ng :business (":business" o "kami"). Libre ang serbisyo sa ngayon. Kung magkakaroon ng bayad na features sa hinaharap, aabisuhan ka muna namin at hindi ka sisingilin nang walang pahintulot mo.',
                'Hindi HR, payroll, o leave-approval system ang SinoWala. Ang pag-file ng lakad dito ay hindi pumapalit sa anumang patakaran ng kumpanya mo tungkol sa leave.',
            ]],
            ['title' => 'Ang account mo', 'body' => [
                ['list' => [
                    'Dapat 18 taong gulang ka pataas, at totoo ang pangalan at email na ilalagay mo.',
                    'Ikaw ang responsable sa pag-iingat ng password mo at sa lahat ng nangyayari sa account mo. I-on ang two-factor authentication.',
                    'Isang tao, isang account. Huwag ipagamit ang account mo sa iba.',
                    'Abisuhan kami agad sa :email kung sa tingin mo ay may ibang nakapasok sa account mo.',
                ]],
            ]],
            ['title' => 'Mga kumpanya', 'body' => [
                ['list' => [
                    'Ang gumawa ng kumpanya ang unang admin nito. Kailangang ma-approve namin ang kumpanya bago bumukas ang team calendar.',
                    'Ang admin ay responsable sa kung sino ang iniimbita nila, at dapat may karapatan silang katawanin ang kumpanya.',
                    'Ang mga araw na Wala at notes ay pag-aari ng bawat user, hindi ng kumpanya. Kapag umalis o inalis ang isang miyembro, o na-delete ang kumpanya, sa user pa rin ang kalendaryo niya.',
                    'Kailangan laging may kahit isang admin ang kumpanya.',
                ]],
            ]],
            ['title' => 'Tamang paggamit', 'body' => [
                'Hindi mo puwedeng:',
                ['list' => [
                    'Gamitin ang SinoWala para sa anumang ilegal, mapanlinlang, o mapanakit na gawain.',
                    'Mag-upload o magsulat ng nilalamang lumalabag sa karapatan ng iba.',
                    'Subukang pasukin ang account o data ng ibang tao, o lampasan ang anumang security measure.',
                    'Mag-scrape, mag-spam, o magpadala ng labis na requests na nakakaabala sa serbisyo.',
                    'Gumawa ng pekeng kumpanya o magpanggap na ibang tao o organisasyon.',
                    'I-reverse engineer, kopyahin, o ibenta ang serbisyo nang walang pahintulot namin.',
                ]],
            ]],
            ['title' => 'Suspension at pagtatapos', 'body' => [
                'Maaari naming i-suspend o tapusin ang isang account o kumpanya kung lumabag ito sa Terms na ito, kung may panganib sa seguridad ng ibang user, o kung hinihingi ng batas. Kung makatwiran, aabisuhan ka muna namin at bibigyan ng pagkakataong magpaliwanag.',
                'Puwede mong i-delete ang account mo anumang oras sa Settings. Puwede mo ring i-download muna ang data mo.',
            ]],
            ['title' => 'Availability ng serbisyo', 'body' => [
                'Sinisikap naming laging gumagana ang SinoWala, pero ibinibigay ito "as is" at "as available". Maaaring may maintenance, aberya, o pagbabago sa features. Gumagawa kami ng araw-araw na backup, pero hindi namin magagarantiya na hindi kailanman mawawala ang data.',
            ]],
            ['title' => 'Limitasyon ng pananagutan', 'body' => [
                'Hangga\'t pinapayagan ng batas, hindi mananagot ang :business sa anumang hindi direktang pinsala, nawalang kita, o pinsalang dulot ng maling impormasyon sa kalendaryo, ng pagkaantala ng serbisyo, o ng paggamit mo ng SinoWala.',
                'Dahil libre ang serbisyo, ang kabuuang pananagutan namin sa anumang claim ay hindi lalampas sa ₱1,000. Walang nakasulat dito na naglilimita sa pananagutan na hindi puwedeng limitahan sa ilalim ng batas ng Pilipinas, kabilang ang Consumer Act at ang Data Privacy Act.',
            ]],
            ['title' => 'Intellectual property', 'body' => [
                'Sa amin ang pangalan, logo, disenyo, at software ng SinoWala. Sa iyo ang data na inilalagay mo; binibigyan mo lang kami ng pahintulot na i-store at ipakita ito ayon sa mga setting mo para mapatakbo ang serbisyo.',
            ]],
            ['title' => 'Mga pagbabago sa Terms', 'body' => [
                'Maaari naming baguhin ang Terms na ito. Ia-update namin ang petsa at bersyon sa itaas, at kung malaki ang pagbabago, hihilingin naming tanggapin mo ulit ito bago ka makapagpatuloy.',
            ]],
            ['title' => 'Batas na sinusunod', 'body' => [
                'Ang Terms na ito ay sakop ng mga batas ng Republika ng Pilipinas. Anumang hindi pagkakaunawaan ay dadalhin sa tamang korte sa [PLACEHOLDER: lungsod], Pilipinas, maliban kung iba ang itinakda ng batas.',
            ]],
            ['title' => 'Makipag-ugnayan', 'body' => [
                ':business · :address · :email',
            ]],
        ],
    ],

    'cookies' => [
        'title' => 'Cookie Policy',
        'meta' => 'Listahan ng lahat ng cookies na ginagamit ng SinoWala, kung para saan, at gaano katagal.',
        'intro' => 'Ang cookie ay maliit na file na sine-save ng browser mo. Kaunti lang ang ginagamit ng SinoWala, at ang optional na cookies ay naka-off hanggang pumayag ka.',
        'sections' => [
            ['title' => 'Mga cookie na ginagamit namin', 'body' => [
                ['table' => [
                    'head' => ['Pangalan', 'Para saan', 'Gaano katagal', 'Uri'],
                    'rows' => [
                        ['sinowala_session', 'Pinapanatili kang naka-log in at tinatandaan ang mga ginagawa mo sa isang pagbisita. Naka-encrypt.', '2 oras mula sa huling galaw', 'Strictly necessary'],
                        ['XSRF-TOKEN', 'Proteksyon laban sa cross-site request forgery (CSRF) sa mga form.', '2 oras', 'Strictly necessary'],
                        ['remember_web_…', 'Kung pinili mo ang "Tandaan ako", hindi ka na kailangang mag-log in ulit sa device na iyon.', 'Hanggang mag-log out ka (hanggang 400 araw)', 'Strictly necessary'],
                        ['sw_consent', 'Tinatandaan ang pinili mo sa cookie banner para hindi ka na tanungin ulit.', '6 na buwan', 'Strictly necessary'],
                    ],
                ]],
            ]],
            ['title' => 'Analytics', 'body' => [
                'Kung may naka-set up na analytics ang may-ari ng site, gagamit lang kami ng privacy-friendly na serbisyo (hal. Plausible) na hindi gumagamit ng cookies at hindi sumusubaybay sa iyo sa ibang site. Hindi ito maglo-load hangga\'t hindi ka pumapayag sa "Analytics" sa Cookie settings.',
                'Kung walang analytics na naka-set up, wala itong ginagawa kahit pumayag ka.',
            ]],
            ['title' => 'Paano baguhin ang pinili mo', 'body' => [
                'Pindutin ang "Cookie settings" sa footer ng anumang page para buksan ulit ang mga opsyon. Puwede mo ring burahin ang cookies sa settings ng browser mo; kapag binura mo ang session cookie, mala-log out ka.',
            ]],
            ['title' => 'Walang third-party na tracking', 'body' => [
                'Walang ads, walang social media pixels, at walang font o script na kinukuha sa ibang website. Naka-host sa sarili naming server ang font na ginagamit namin.',
            ]],
        ],
    ],

    'accessibility' => [
        'title' => 'Accessibility Statement',
        'meta' => 'Ang pangako ng SinoWala sa accessibility: WCAG 2.2 Level AA, keyboard, screen readers, at paano mag-report ng problema.',
        'intro' => 'Gusto naming magamit ng lahat ang SinoWala, kasama ang mga gumagamit ng keyboard lang, screen reader, voice control, o malaking text.',
        'sections' => [
            ['title' => 'Ang target namin', 'body' => [
                'Sinisikap naming sundin ang Web Content Accessibility Guidelines (WCAG) 2.2, Level AA, sa buong website at app.',
            ]],
            ['title' => 'Ang ginawa namin', 'body' => [
                ['list' => [
                    'Gumagana ang lahat gamit ang keyboard, may malinaw na focus outline, at may "Lumaktaw sa content" na link.',
                    'Sinusunod ng kalendaryo ang ARIA grid pattern: arrow keys para lumipat ng araw, Home/End para sa linggo, Page Up/Page Down para sa buwan, at Enter para buksan ang araw. May label ang bawat araw, hal. "Martes, Oktubre 27: 1 wala — Juan dela Cruz".',
                    'Hindi kulay lang ang batayan: laging nakasulat ang "Wala" (at AM/PM) sa orange na araw.',
                    'Hindi bababa sa 4.5:1 ang contrast ng text, at 3:1 sa mga border ng controls.',
                    'May nakikitang label ang bawat field, at inaanunsyo ang mga error at notification sa screen readers.',
                    'Ang mga dialog ay nagpapanatili ng focus sa loob, nagsasara sa Escape, at ibinabalik ang focus sa pinanggalingan.',
                    'Hindi bababa sa 44×44 pixels ang mga pindutan, gumagana ang layout hanggang 200% zoom at sa 320px na lapad, at walang galaw kapag naka-on ang "reduce motion".',
                    'Puwedeng i-off ang single-key keyboard shortcuts sa Settings (WCAG 2.1.4).',
                ]],
            ]],
            ['title' => 'Mga kilalang limitasyon', 'body' => [
                'Ang QR code para sa two-factor authentication ay may kasamang setup key na puwedeng i-type nang manual. Kung may makita kang ibang problema, sabihin sa amin.',
            ]],
            ['title' => 'Mag-report ng problema', 'body' => [
                'Kung may bahagi ng SinoWala na hindi mo magamit, mag-email sa :email o gamitin ang contact form (:contact_url). Sabihin ang page, ang ginagamit mong device at assistive technology, at ano ang nangyari. Sasagot kami sa loob ng 5 araw na may pasok.',
            ]],
        ],
    ],
];
