# SinoWala

**Sino'ng wala ngayon?** Isang availability calendar para sa mga indibidwal at maliliit na negosyo sa Pilipinas. Bawat tao ang nagmamarka ng mga araw na *Wala* sila; Available ang lahat ng ibang araw. Kapag kasali sa kumpanya, makikita ng boss sa isang team calendar kung sino ang andito at sino ang wala. Walang leave types, walang dahilan, walang approval — sadya, para sa privacy.

- **Stack:** PHP 8.3+, Laravel 13, MariaDB 10.11+, Blade + Livewire 3 + Alpine.js + Tailwind CSS 4 (Vite), Laravel Fortify (auth + 2FA), spatie/laravel-backup.
- **Wika:** Lahat ng copy ay nasa `lang/fil/*.php` (at `lang/fil.json` para sa mga email ng Laravel). Doon mo i-edit.
- **Walang automated tests** (ayon sa brief). Nasa ibaba ang manual testing checklist.

---

## Laman

1. [Local setup](#1-local-setup)
2. [Deployment](#2-deployment)
3. [Production checklist](#3-production-checklist)
4. [Manual testing checklist](#4-manual-testing-checklist)
5. [Mga desisyong ginawa ko](#5-mga-desisyong-ginawa-ko-kung-saan-tahimik-ang-brief)
6. [Reference: commands, files, at mga kilalang limitasyon](#6-reference)

---

## 1. Local setup

### Kailangan

| Tool | Bersyon | Bakit |
|---|---|---|
| PHP | **8.3 o mas bago** (8.4 ang ginamit sa build) | Laravel 13 ay `php ^8.3` |
| PHP extensions | `pdo_mysql`, `mbstring`, `openssl`, `intl`, `fileinfo`, `gd`, `zip`, `curl`, `bcmath`, `sodium` | Laravel, 2FA QR code, export ng data (.zip), backups |
| Composer | 2.x | PHP dependencies |
| Node.js | 22 o mas bago | Vite build (at ang design skill sa `.agents/`) |
| MariaDB | 10.11 o mas bago (gumagana rin sa 10.4 ng XAMPP para sa local) | Database |

> **Paalala para sa XAMPP:** Ang XAMPP na naka-install sa computer na ito ay may **PHP 8.2** — hindi sapat para sa Laravel 13. Sa build na ito, gumamit ako ng hiwalay na portable PHP 8.4 sa `C:\Users\vsmi2\tools\php84` (hindi ginalaw ang XAMPP). Para sa sarili mong setup, piliin ang isa: (a) mag-download ng XAMPP na may PHP 8.3+, (b) gumamit ng Laragon, o (c) gamitin ang portable PHP 8.4 na iyon gamit ang `php artisan serve`.

### Windows — XAMPP o Laragon

```powershell
# 1. Kunin ang code at i-install
cd C:\xampp\htdocs\SinoWala        # o C:\laragon\www\SinoWala
composer install
npm install

# 2. Environment
copy .env.example .env
php artisan key:generate
#    Buksan ang .env at itakda: DB_DATABASE, DB_USERNAME, DB_PASSWORD, SUPER_ADMIN_EMAIL

# 3. Database (gumawa muna ng database na "sinowala", utf8mb4_unicode_ci, sa phpMyAdmin o HeidiSQL)
php artisan migrate --seed
php artisan db:seed --class=DemoSeeder   # opsyonal: sample na kumpanya at users

# 4. Assets at server
npm run build          # o: npm run dev  (habang nagde-design)
php artisan serve      # bukas sa http://localhost:8000
```

- **Laragon:** awtomatikong may virtual host (`http://sinowala.test`). Itakda ang `APP_URL` doon.
- **XAMPP Apache:** ituro ang DocumentRoot sa `SinoWala\public`, o gamitin na lang ang `php artisan serve`.
- **Emails sa local:** `MAIL_MAILER=log` — lumalabas ang verification at reset emails sa `storage/logs/laravel-YYYY-MM-DD.log`. Kopyahin ang link mula roon.
- **Queue sa local:** `php artisan queue:work` sa hiwalay na terminal (o `php artisan schedule:work`, na nagpapatakbo ng queue bawat minuto).
- **Backups sa XAMPP:** ang Spatie backup ay naghahanap ng `mariadb-dump`, pero `mysqldump.exe` lang ang nasa XAMPP (MariaDB 10.4). Kopyahin ang `C:\xampp\mysql\bin\mysqldump.exe` bilang `mariadb-dump.exe` sa isang folder at ilagay ang folder na iyon sa `DB_DUMP_BINARY_PATH`. Hindi ito kailangan sa MariaDB 10.11+.

**Demo accounts** (pagkatapos ng `DemoSeeder`), password: `DemoPass-2026`

| Email | Ano |
|---|---|
| `maria@demo.sinowala.test` | Admin (boss) ng "Kapihan sa Kanto" (active) |
| `ana@`, `ben@`, `dina@demo.sinowala.test` | Members, may mga Wala ngayong linggo |
| `paolo@demo.sinowala.test` | Personal user, may notes |

**Super Admin:** mag-sign up sa website gamit ang email na nasa `SUPER_ADMIN_EMAIL`, i-verify ang email, tapos i-on ang two-factor sa Settings. Saka lang bubukas ang `/admin`. Fallback: `php artisan sinowala:make-super-admin you@example.com`.

**Styleguide:** `http://localhost:8000/styleguide` (lumalabas lang kapag `APP_ENV=local`).

### Mac — Herd o Valet

```bash
cd ~/Herd            # o ang folder na naka-"valet park"
git clone <repo> sinowala && cd sinowala
composer install && npm install
cp .env.example .env && php artisan key:generate
# Gumawa ng database (DBngin o `brew install mariadb` + `mariadb -e "CREATE DATABASE sinowala CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"`)
php artisan migrate --seed
npm run build
# Herd: http://sinowala.test  ·  Valet: `valet link sinowala && valet secure sinowala`
```

---

## 2. Deployment

Pareho ang build sa dalawang klase ng server:

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build          # o i-build sa computer mo at i-upload ang public/build
php artisan migrate --force
php artisan optimize             # config, route, event at view cache
php artisan storage:link         # hindi required ng SinoWala, pero ligtas
```

> Tuwing magbabago ka ng `.env` sa production: `php artisan optimize:clear && php artisan optimize`.

### A. Shared hosting (Hostinger / cPanel)

1. **PHP version:** sa hPanel → *Advanced → PHP Configuration* (o cPanel → *Select PHP Version*), piliin ang **PHP 8.3 o 8.4**. I-enable ang `intl`, `fileinfo`, `gd`, `zip`, `pdo_mysql`, `sodium`.
2. **Database:** gumawa ng MariaDB/MySQL database at user. Itala ang host, pangalan, user at password para sa `.env`.
3. **I-upload ang code** sa labas ng `public_html`, hal. `/home/USER/sinowala` (Git deploy, SSH, o File Manager + zip). Isama ang `vendor/` at `public/build/` kung walang Composer/Node sa server.
4. **Ituro ang domain sa `/public`:**
   - *Pinakamaganda:* sa cPanel → *Domains*, itakda ang Document Root ng domain sa `/home/USER/sinowala/public`. Sa Hostinger, gumawa ng addon/subdomain na may custom na document root kung pinapayagan.
   - *Kung hindi mapalitan ang document root:* palitan ang `public_html` ng symlink: `rm -rf ~/public_html && ln -s ~/sinowala/public ~/public_html` (sa SSH). **Huwag** ilagay ang buong project sa loob ng `public_html`.
5. **`.env`:** kopyahin ang `.env.example`, itakda ang `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain.ph`, DB, MAIL (SMTP ng Hostinger o ibang provider), `SUPER_ADMIN_EMAIL`, mga `BUSINESS_*` at `DPO_*`. Patakbuhin ang `php artisan key:generate` **isang beses lang**.
6. **Cron (scheduler + queue):** sa hPanel → *Advanced → Cron Jobs* (o cPanel → *Cron Jobs*), bawat minuto:
   ```
   * * * * * cd /home/USER/sinowala && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
   ```
   Gamitin ang tamang PHP path (hal. `/opt/alt/php83/usr/bin/php` sa CloudLinux). Dahil `QUEUE_VIA_SCHEDULER=true`, ang scheduler din ang nagpapatakbo ng `queue:work --stop-when-empty` bawat minuto — walang Supervisor na kailangan.
7. **Backups:** itakda ang `DB_DUMP_BINARY_PATH` kung wala sa PATH ang `mariadb-dump`/`mysqldump` (tingnan gamit ang `which mariadb-dump`). Nasa `storage/app/backups` ang mga zip; i-download paminsan-minsan sa labas ng server.
8. **SSL:** i-on ang libreng SSL (Let's Encrypt) sa hPanel → *Security → SSL* o cPanel → *SSL/TLS Status → Run AutoSSL*. Sa production, nire-redirect ng app ang HTTP papuntang HTTPS at nagpapadala ng HSTS.
9. **Permissions:** `chmod -R 775 storage bootstrap/cache`.
10. **Hostinger, kapag hindi ma-set sa 8.4 ang PHP ng domain:** PHP 8.4+ ang kailangan ng `composer.lock` (Symfony 8). Ilagay sa unang linya ng `.htaccess` ng `public_html`: `AddHandler application/x-httpd-alt-php84___lsphp .php`, at gamitin ang `/opt/alt/php84/usr/bin/php` sa SSH at sa cron. Kapag kinopya ang `public/` sa `public_html` (sa halip na symlink), palitan ang `__DIR__.'/../` sa `index.php` ng buong path ng app.

### B. VPS (Ubuntu 24.04 + Nginx + PHP-FPM + MariaDB + Certbot + Supervisor)

```bash
sudo apt update && sudo apt install -y nginx mariadb-server unzip git supervisor \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip \
  php8.3-intl php8.3-gd php8.3-bcmath
# Composer: https://getcomposer.org/download/  ·  Node 22: https://github.com/nodesource/distributions
sudo mysql_secure_installation
sudo mariadb -e "CREATE DATABASE sinowala CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE USER 'sinowala'@'localhost' IDENTIFIED BY 'MAHABANG_PASSWORD';
  GRANT ALL ON sinowala.* TO 'sinowala'@'localhost'; FLUSH PRIVILEGES;"

sudo mkdir -p /var/www/sinowala && sudo chown $USER:www-data /var/www/sinowala
git clone <repo> /var/www/sinowala && cd /var/www/sinowala
composer install --no-dev --optimize-autoloader && npm ci && npm run build
cp .env.example .env && php artisan key:generate   # tapos i-edit ang .env
php artisan migrate --force && php artisan optimize
sudo chown -R $USER:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache
```

**Nginx** (`/etc/nginx/sites-available/sinowala`):

```nginx
server {
    listen 80;
    server_name sinowala.ph www.sinowala.ph;
    root /var/www/sinowala/public;
    index index.php;
    charset utf-8;
    client_max_body_size 2m;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location = /favicon.ico { access_log off; log_not_found off; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/sinowala /etc/nginx/sites-enabled/ && sudo nginx -t && sudo systemctl reload nginx
sudo apt install -y certbot python3-certbot-nginx && sudo certbot --nginx -d sinowala.ph -d www.sinowala.ph
```

**Cron** (`crontab -e -u www-data`):
```
* * * * * cd /var/www/sinowala && php artisan schedule:run >> /dev/null 2>&1
```

**Supervisor para sa queue** (`/etc/supervisor/conf.d/sinowala-worker.conf`), at itakda ang `QUEUE_VIA_SCHEDULER=false` sa `.env`:
```ini
[program:sinowala-worker]
command=php /var/www/sinowala/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
user=www-data
numprocs=1
autostart=true
autorestart=true
stopwaitsecs=3600
redirect_stderr=true
stdout_logfile=/var/www/sinowala/storage/logs/worker.log
```
```bash
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start sinowala-worker
# Pagkatapos ng bawat deploy:
php artisan optimize && php artisan queue:restart
```

Kung nasa likod ka ng Cloudflare o load balancer, itakda ang `TRUSTED_PROXIES=*` para tama ang HTTPS detection at IP sa audit log.

---

## 3. Production checklist

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=error`
- [ ] `APP_KEY` naka-set (at **naka-backup sa ligtas na lugar** — kapag nawala, hindi na mababasa ang encrypted notes)
- [ ] `APP_URL` ay `https://…`; may SSL; nagre-redirect ang `http://` sa `https://`; may `Strict-Transport-Security` header
- [ ] Document root ay `/public` lang (subukan: `https://domain/.env` ay dapat 404)
- [ ] `SESSION_SECURE_COOKIE=true` (default na ito kapag production) at `SESSION_ENCRYPT=true`
- [ ] Mail: nakapag-sign up ka at natanggap ang verification email; gumagana ang password reset; dumadating sa `SUPER_ADMIN_EMAIL` ang contact form
- [ ] Cron tumatakbo: `php artisan schedule:list`; may bagong `last_run` sa logs; nauubos ang `jobs` table
- [ ] Backups: `php artisan backup:run --only-db` ay "Backup completed!"; `php artisan backup:list` ay Healthy
- [ ] `storage/` at `bootstrap/cache/` ay writable ng web server user
- [ ] `SUPER_ADMIN_EMAIL` tama; naka-on ang 2FA ng Super Admin; nakatago ang recovery codes
- [ ] Napalitan ang lahat ng `[PLACEHOLDER]` (`BUSINESS_*`, `DPO_*` sa `.env`, at ang lungsod sa Terms, `lang/fil/legal.php`)
- [ ] Na-review ng abogado ang Privacy Policy at Terms
- [ ] `php artisan optimize` pagkatapos ng bawat deploy
- [ ] Hindi naka-commit ang `.env` (`git status` malinis)
- [ ] `composer audit` at `npm audit` malinis

---

## 4. Manual testing checklist

Gawin sa **phone (≤ 390px), tablet (768px), at desktop (1280–1440px)**. Gamitin ang demo accounts o gumawa ng bago.

### Public website
- [ ] Home: 3D na hero (pindutin ang isang araw sa 3D na Nobyembre; tumataas ang block, lumalabas ang "Ikaw", at nagbabago ang "… andito ang X sa 5"), tatlong frosted card, floating sign-up bar (lumalabas pagkalampas ng hero; ang X ay nagsasara at tinatandaan sa device), section index sa kanan sa napakalapad na screen, Paano gumagana, Para sa sarili, Para sa kumpanya (tatlong tab: Ngayon / Team calendar / Team page), privacy table, FAQ, "Simulan na" form (dinadala sa sign-up na nakapili na ang path)
- [ ] Header nav (mga anchor sa home + FAQ); sa phone/tablet, ang Menu button ay nagbubukas at nagsasara (Escape din)
- [ ] Tungkol, FAQ, Paano gamitin, Contact, Privacy Policy, Terms of Use, Cookie Policy, Data Privacy Request, Accessibility — bawat isa ay may sariling title, "Huling na-update" + bersyon sa legal pages
- [ ] Footer: lahat ng link, "Cookie settings" ay nagbubukas ng dialog, © kasalukuyang taon, business name
- [ ] Contact: blangkong submit → error sa tabi ng bawat field, hindi nabubura ang form; matagumpay na submit → success message, lumalabas sa `/admin/messages`, may email sa admin (log); 6th submit sa isang oras → 429 page
- [ ] Data Privacy Request: parehong checks; lumalabas sa `/admin/privacy-requests`
- [ ] `/sitemap.xml` (public pages lang), `/robots.txt` (disallow `/app`, `/admin`, `/u/`), view-source: title, description, canonical, Open Graph, JSON-LD
- [ ] Error pages: `/kahit-ano` (404), mag-submit ng form pagkatapos ng 2 oras (419), `php artisan down` (503) at `php artisan up`

### Cookie banner
- [ ] Unang bisita: nasa itaas ang banner, hindi tinatakpan ang content; tatlong button na pareho ang itsura
- [ ] "Kailangan lang" / "Tanggapin lahat" → nawawala ang banner, may `sw_consent` cookie (6 buwan), may row sa `consent_logs`
- [ ] "Settings" → dialog, gumagana ang Analytics checkbox, Escape ay nagsasara
- [ ] Walang analytics script na naglo-load hangga't walang `ANALYTICS_ID` **at** pahintulot (tingnan sa DevTools → Network)

### Sign-up, log in, account
- [ ] Sign-up "Para sa sarili ko" → verification email → `/app`
- [ ] Sign-up "Para sa kumpanya ko" → banner na "Hinihintay pa ang approval" sa Team; gumagana ang sariling kalendaryo
- [ ] Sign-up "Sumali sa kumpanya" gamit ang code; mali/lumang code → generic na error; invite link `/join/…` habang naka-log out → sign-up na may invite
- [ ] Hindi puwedeng mag-sign up nang walang check sa Terms; mahinang password (`password`) → malinaw na error
- [ ] Log in na mali ang password → generic na mensahe; 6 na mali sa isang minuto → 429
- [ ] Forgot password: kahit hindi existing ang email, pareho ang mensahe; gumagana ang reset link
- [ ] Two-factor: i-on → QR + setup key → i-confirm → recovery codes; mag-log out at mag-log in → hihingan ng code; recovery code ay gumagana
- [ ] Settings → Browser sessions: mag-log in sa ibang browser, "I-log out ang ibang device" (kailangan ng password)
- [ ] Palitan ang `terms_version` sa `config/sinowala.php` → sa susunod na request, hinihinging tanggapin ulit

### App — bawat user
- [ ] "Mag-file ng lakad" (button o **N**): isang araw (Buong araw / AM / PM), range (naka-disable ang AM/PM, may "Laktawan ang Sabado at Linggo"), "Hanggang" bago ang "Mula" → error, range na > 62 araw → error
- [ ] Private note: lumalabas lang sa sarili mong kalendaryo; **wala** sa team calendar ng iba, sa share link, at sa admin
- [ ] I-edit at i-delete ang isang lakad (pindutin ang araw → I-edit; o ang lapis sa "Ngayon")
- [ ] Kalendaryo ko: Buwan / Linggo / Listahan; ← → at "Ngayon"; default view: Listahan sa phone, Linggo sa tablet, Buwan sa desktop
- [ ] Share link: i-on → buksan sa incognito (Available/Wala lang, 3 buwan, `noindex`); i-regenerate → patay ang luma; i-off → 404
- [ ] I-download ang data ko → .zip na may JSON at CSV (kasama ang notes mo)
- [ ] I-delete ang account ko: maling password → error; tamang password (+ 2FA code kung naka-on) → nabura, naka-log out

### App — kumpanya
- [ ] Member: "Ngayon" → Team/Ako; "Wala ngayon", "Andito ngayon: X sa Y", "Susunod na lakad"; Team calendar Buwan at Linggo (Lunes ang simula, may bilang ng andito bawat araw); "/" para hanapin ang tao
- [ ] Member: hindi mabuksan ang `/app/team` (403)
- [ ] Boss (`maria@…`): Team page — kopyahin ang link at code, gumawa ng bago (hindi na gumagana ang luma), gawing admin, gawing member (hindi puwede kung iisa lang ang admin), alisin sa team
- [ ] Boss: palitan ang pangalan ng kumpanya; umalis (bawal kung ikaw lang ang admin at may iba pang members); i-delete ang kumpanya (kailangang i-type ang pangalan) → personal na ulit ang lahat, buo pa ang kalendaryo nila
- [ ] Member: umalis sa kumpanya → buo pa rin ang sariling kalendaryo

### Super Admin
- [ ] Walang 2FA → `/admin` nire-redirect sa Settings
- [ ] Dashboard: totals, sign-ups bawat linggo, pending companies
- [ ] Companies: search, filter, pagination; approve, suspend, reactivate, delete (type-the-name)
- [ ] Users: suspend (nala-log out siya sa lahat ng device; hindi makapag-log in) at reactivate; hindi masu-suspend ang sarili
- [ ] Messages at Privacy requests: markahang nabasa / tapos
- [ ] Audit log: bawat aksyon sa itaas ay may sino, ano, kailan, IP
- [ ] Hindi kailanman makita ang kalendaryo o notes ng sinuman (bilang lang ng Wala days sa Users)

### Keyboard lang
- [ ] Tab mula sa itaas: "Lumaktaw sa content" ang unang focus; laging kita ang 2px focus ring
- [ ] Kalendaryo: Tab papasok sa grid, ← → ↑ ↓, Home/End, Page Up/Page Down (lilipat ng buwan at mananatili ang focus), Enter/Space ay nagbubukas ng araw, Escape ay nagsasara at bumabalik ang focus sa araw
- [ ] Shortcuts: N, T, ← / → (sa labas ng grid), M, W, /, ? — hindi gumagana habang nagta-type sa field; i-off sa Settings → hindi na gumagana
- [ ] Bawat dialog: nananatili ang focus sa loob, Escape ay nagsasara, bumabalik ang focus sa button

### Screen reader (NVDA sa Windows, VoiceOver sa Mac/iPhone, TalkBack sa Android)
- [ ] Landmarks: banner, navigation, main, contentinfo; isang h1 bawat page
- [ ] Bawat araw sa kalendaryo ay binabasa nang buo, hal. "Martes, Oktubre 27: 1 wala — Juan dela Cruz"
- [ ] Inaanunsyo ang errors ng form, ang toasts ("Na-file na…"), at ang pagpapalit ng buwan
- [ ] Ang orange na araw ay laging may salitang "Wala" (hindi kulay lang)

### Iba pa
- [ ] 200% zoom at 320px na lapad: walang horizontal scroll sa page
- [ ] "Reduce motion" sa OS: walang animation
- [ ] iPhone na may notch: walang natatakpan sa ibaba (safe areas)
- [ ] iPhone (Safari): "I-install sa phone" → lumalabas ang 3 hakbang → Share → Add to Home Screen → bumubukas ang SinoWala nang walang address bar, may logo habang naglo-load
- [ ] Android (Chrome): "I-install sa phone" → lumalabas agad ang install prompt ng Chrome → nasa home screen at app list
- [ ] Sa loob ng installed app: wala na ang "I-install sa phone"; naka-airplane mode → "Walang internet ngayon"

---

## 5. Mga desisyong ginawa ko (kung saan tahimik ang brief)

**Produkto**
- **Half-day ay para sa isang araw lang.** Kapag range ang pinili, buong araw ang bawat araw (naka-disable ang AM/PM at may paliwanag).
- **Range:** hanggang 62 araw bawat file; may opsyong laktawan ang Sabado at Linggo. Kapag may existing na lakad sa range, ina-update ito (hindi duplicate). Ang note ay kinokopya sa bawat araw ng range.
- **Puwedeng mag-file ng lakad hanggang 1 taon pabalik at 2 taon pasulong.**
- **"Andito ngayon: X sa Y"** — ang half-day ay binibilang na *andito* (dahil babalik sila), at sinasabi ito sa ilalim ng numero ("kasama ang 1 na kalahating araw lang").
- **Ang mga suspended na user** ay hindi lumalabas sa team calendar.
- **Pagsali:** active na kumpanya lang ang tumatanggap ng bagong members. Iisa ang error para sa mali, luma, at hindi pa approved na code (para hindi malaman kung existing ang code).
- **Code:** 6 na character mula sa `ABCDEFGHJKMNPQRSTUVWXYZ23456789` (walang 0/O/1/I/L para hindi magkamali sa pagbasa). Ang invite link ay 40-character na random token. Pinapalitan ang dalawa nang sabay.
- **Pending company:** puwedeng buksan ng boss ang Team page (makikita ang members), pero lalabas lang ang invite link/code kapag active na.
- **Huling member na umalis** → awtomatikong nabubura ang kumpanya. **Huling admin** ay hindi puwedeng umalis o mag-delete ng account hangga't may ibang members — mag-promote muna o i-delete ang kumpanya. Kung siya lang ang member, kasama nang nabubura ang kumpanya kapag dinelete niya ang account.
- **Admin ay puwedeng mag-alis ng ibang admin** (laging may natitirang admin dahil hindi niya maaalis ang sarili).
- **Share page** ay nagpapakita ng *unang pangalan* ng tao (para alam ng tumitingin kung kanino ito). Walang email, notes, o kumpanya. 48-character na token.
- **Super Admin** ay naka-flag sa `users.is_super_admin`: awtomatikong na-o-on kapag *verified* ang email na tugma sa `SUPER_ADMIN_EMAIL`, o sa pamamagitan ng `sinowala:make-super-admin`. Kapag pinalitan ng Super Admin ang email niya, nawawala ang flag. Ang `/admin` ay 404 para sa iba (hindi 403, para hindi malaman na may admin area).
- **Ang Super Admin ay nakakakita ng bilang ng Wala days bawat user** sa Users list (isang numero, walang petsa o notes) — "counts only".
- **Pagsuspinde ng user** ay nagla-log out sa kanya sa lahat ng device kaagad.

**Privacy at retention**
- Mga retention na hindi binanggit ng brief: consent logs at natapos na privacy requests — 2 taon; database backups — 14 na araw. Ang hindi pa natatapos na privacy request ay hindi binubura.
- Kapag nag-delete ng account: nabubura ang user, absences, notes, membership, share token, consent logs at sessions. Ang privacy requests na ginawa niya ay nananatili (walang user link) bilang patunay na nasagot ito. Ang audit log entries kung saan siya ang *actor* ay nananatili na walang pangalan.
- Ang export ay isang `.zip` na may `sinowala-data.json` at `sinowala-absences.csv` (UTF-8 na may BOM para mabuksan nang tama sa Excel).
- Naka-encrypt ang session (`SESSION_ENCRYPT=true`) bukod sa encrypted na notes.
- Ang `password_reset` at `throttled` na mensahe ay pareho sa success, para hindi malaman kung existing ang email.

**Seguridad**
- **CSP:** `script-src 'self' 'nonce-…' 'unsafe-eval'`. Kailangan ang `'unsafe-eval'` dahil ang Alpine na kasama sa **Livewire 3** ay nag-e-evaluate ng `x-*` expressions (ang CSP-safe build ay nasa Livewire 4 lang). Nonce pa rin ang lahat ng inline script, kaya hindi tatakbo ang na-inject na `<script>`. `style-src 'self' 'unsafe-inline'` (para sa inline heights ng chart at mga style na isinusulat ng Alpine). Walang external na font, script o image.
- **Rate limits:** login 5/min bawat email+IP (Fortify); 2FA 5/min; sign-up 10/oras bawat IP; password reset 3/min bawat email+IP at 20/oras bawat IP; join code 5/min; contact at privacy request 5/oras bawat IP; share page 30/min; consent log 20/min; export 10/oras; pag-file ng lakad 30/min.
- **Honeypot** sa contact at privacy forms: kapag napunan, mukhang success pero walang nase-save.
- Ang `/admin`, `/join/…` at `/u/…` ay may `X-Robots-Tag: noindex`; ang buong app ay `noindex` sa meta.
- `local` disk ng Laravel ay hindi nagse-serve ng files (`serve => false`).

**Design (taste skill)**
- **App screens** — style na "Achromatic ledger": walang kulay ang chrome (Tinta ang primary), at ang Kahel ay para lang sa mga "Wala" tile. Ang signature element ay ang team week na may hilera ng "Andito X/Y" bawat araw.
- **Public site** — direction na "Split stage": ang ideya ay *"ang isang tile ng logo na lumabas sa grid ay nagiging Wala tile ng team week"*. Ang live na bagay sa home ay ang halimbawang team week na puwede mong pindutin.
- **Kung saan ko sinunod ang brief kaysa sa skill (dapat mong malaman):**
  - Hinihingi ng skill ang mga totoong litrato/video at isang motion layer na may scroll entrances. Ipinagbabawal ng brief ang stock photos at scroll-triggered animation, kaya **walang litrato at walang scroll animation**; ang "pictures" ay ang totoong UI ng produkto (HTML, may `data-ui`). Ang warnings na `no-real-pictures`, `no-footage`, `still-page` at `few-pictures` ng inspector ay sadyang hindi inayos.
  - Pinapayagan ng brief ang 1px Abo borders; ginamit ko ito sa app screens at forms, pero inalis sa public pages kung saan dekorasyon lang.
  - Legal pages at forms ay hindi "poster" pages, kaya hindi nila natutugunan ang `focal-point` at katulad na art-direction checks ng inspector.
- **Cookie banner** ay nasa itaas ng page (bahagi ng daloy, hindi naka-float), para hindi nito matakpan ang anumang content.
- **Radius:** 6px sa controls at tiles, 8px sa cards at dialogs. Isang shadow level lang, para sa menus, dialogs at toasts.
- **Icons:** Lucide (ISC license), kinopya sa `resources/icons`, 1.75 stroke.
- **Type scale:** 12/14/16/18/24/32/48, at isang mas malaking hero size sa home (56–64px) para sa h1 at sa "Simulan na".

**Teknikal**
- Laravel 13 (pinakabagong stable) + Livewire 3.8 (ang huling v3, ayon sa brief).
- Fortify ang auth backend; sariling Blade views. Ang Livewire form (`App\Livewire\Forms\AbsenceFormData`) ang katumbas ng Form Request para sa pag-file ng lakad; Form Requests ang ginamit sa lahat ng iba.
- Dalawang hiwalay na queued mail (contact at privacy request). Ang verification at reset emails ay agad ipinapadala (hindi queued) para hindi maghintay ng cron.
- Ang `og-image.png` (1200×630) ay ginawa mula sa logo.
- Landing page: ginaya ang istilo ng reference na hiningi (3D na mundo sa hero, frosted glass na cards, pill na header, floating bar na may X). Ang 3D ay totoong CSS 3D (walang WebGL o library) kaya magaan, gumagana sa keyboard (bawat araw ay button na may label), at hindi gumagalaw kapag naka-on ang "reduce motion". Ang glass ay may opaque na fallback kapag walang `backdrop-filter`.
- **PWA ("I-install sa phone"):** `public/site.webmanifest` + `public/sw.js`. Bumubukas ang installed app sa `/app` (login kung hindi pa naka-log in). **Walang page na sine-save ng service worker**, static files lang (CSS/JS, fonts, icons) at isang `offline.html`, dahil may lakad at notes ng tao ang mga page at puwedeng shared ang phone. Hindi ito gumagana offline; sinasabi lang nito nang malinaw na offline ka. Sa Android/desktop Chrome, ang sariling install prompt ng browser ang lumalabas; sa iPhone/iPad (walang prompt ang Apple), dialog na may hakbang na Share → Add to Home Screen, at hiwalay na hakbang kapag nakabukas sa loob ng Facebook/Messenger. May iOS launch screens sa `public/splash/` (gawa mula sa stacked logo). Kapag binago ang `sw.js`, palitan ang `VERSION` para mabura ang lumang cache.
- Ang design skill ay naka-install sa `.agents/skills/design-taste-frontend` (may symlink sa `.claude/skills`). Ang mga output nito ay nasa `.taste-skill/` (naka-ignore sa git). Puwede mong burahin ang `.agents/`, `.claude/` at `skills-lock.json` kung ayaw mong kasama sa repo.

---

## 6. Reference

### Commands

| Command | Ano |
|---|---|
| `php artisan migrate --seed` | Gumawa ng tables (walang required na seed data) |
| `php artisan db:seed --class=DemoSeeder` | Sample data (local lang; tatanggi sa production) |
| `php artisan sinowala:make-super-admin {email}` | Gawing Super Admin ang isang existing na account |
| `php artisan sinowala:prune` | Retention (awtomatikong 2:30 AM araw-araw) |
| `php artisan backup:run --only-db` | Manual na database backup (awtomatikong 3:15 AM) |
| `php artisan schedule:list` | Tingnan ang lahat ng scheduled jobs |
| `./vendor/bin/pint --test` | Code style check |
| `npm run build` / `npm run dev` | Assets |

### Saan i-edit

| Ano | File |
|---|---|
| Lahat ng UI at website copy | `lang/fil/app.php`, `web.php`, `auth.php`, `admin.php`, `ui.php`, `mail.php`, `errors.php`, `validation.php`, `passwords.php`; mga email ng Laravel sa `lang/fil.json` |
| Legal pages | `lang/fil/legal.php` (+ bersyon at petsa sa `config/sinowala.php`) |
| Business name, address, DPO | `.env` → `BUSINESS_*`, `DPO_*` |
| Kulay, font, type scale | `resources/css/app.css` (`@theme`) |
| Components | `resources/views/components/ui/*` (tingnan lahat sa `/styleguide`) |
| Retention periods, max range | `config/sinowala.php` |

### Audits (sa pagtatapos ng build)

- `composer audit` — *No security vulnerability advisories found.*
- `npm audit` — *found 0 vulnerabilities.*

### Mga kilalang limitasyon

- `'unsafe-eval'` sa CSP (tingnan sa itaas) hangga't Livewire 3 ang gamit.
- Walang PWA/offline mode (may `site.webmanifest` lang para sa icon at kulay).
- Ang Bricolage Grotesque ay self-hosted sa `public/fonts` (SIL Open Font License, nasa `public/fonts/OFL.txt`).
