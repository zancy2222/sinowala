# SinoWala — Logo Package (Bakante)

Lahat ng SVG ay naka-outline na ang text, kaya hindi na kailangang i-install ang font para lumabas nang tama ang logo.

## Laman ng package

### `svg/` — pangunahing files (gamitin ito hangga't maaari)
| File | Gamit |
|---|---|
| `sinowala-logo-horizontal.svg` | Pangunahing logo, sa puti o maliwanag na background |
| `sinowala-logo-horizontal-white.svg` | Para sa madilim na background |
| `sinowala-logo-horizontal-black.svg` | Isang kulay lang (print, fax, stamp) |
| `sinowala-logo-stacked.svg` / `-white.svg` | Kapag makitid ang espasyo (login page, splash screen) |
| `sinowala-mark.svg` / `-white.svg` | Mark lang, walang text |
| `sinowala-mark-on-orange.svg` | Mark na ilalagay sa orange na background |
| `sinowala-mark-black.svg` / `-white-mono.svg` | Mark na isang kulay lang |
| `sinowala-wordmark.svg` / `-white.svg` | Text lang, walang mark |
| `sinowala-app-icon.svg` | App icon na may rounded corners |
| `sinowala-app-icon-square.svg` | App icon na square (iOS ang magra-round) |
| `sinowala-app-icon-maskable.svg` | Para sa Android, may dagdag na padding |
| `favicon.svg`, `favicon-16.svg` | Pinasimpleng bersyon para sa browser tab |

### `png/` — para sa mga lugar na hindi tumatanggap ng SVG
Para ito sa email signature, social media, at presentations. Transparent ang background maliban sa app icon.

### `web/` — ilagay lahat sa root ng website (`/public` sa Next.js, Vite, o Laravel)
`favicon.ico`, `favicon.svg`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, `icon-192.png`, `icon-512.png`, `icon-maskable-512.png`, `site.webmanifest`

## Code para sa `<head>`

```html
<link rel="icon" href="/favicon.ico" sizes="48x48">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#12161F">
```

## Logo sa header ng app

```html
<a href="/" aria-label="SinoWala home">
  <img src="/sinowala-logo-horizontal.svg" alt="SinoWala" height="32">
</a>
```

Huwag lagyan ng sabay na `width` at `height` para hindi ma-stretch ang logo. `height` lang ang ilagay.

## Mga kulay

| Pangalan | Hex | Gamit |
|---|---|---|
| Tinta | `#12161F` | Logo, text, at madilim na background |
| Kahel | `#FF5B1F` | Para lang sa "wala": ang lumabas na tile at ang mga araw na may lakad ang tao |
| Papel | `#FFFFFF` | Background |
| Abo | `#E3E5E9` | Mga araw na andito ang tao, borders, at secondary na background |

Huwag gamitin ang Kahel sa maliliit na text dahil mahina ang contrast nito sa puti.

```css
:root {
  --sw-tinta: #12161F;
  --sw-kahel: #FF5B1F;
  --sw-papel: #FFFFFF;
  --sw-abo:   #E3E5E9;
}
```

## Font

**Bricolage Grotesque** (libre, Google Fonts)
- ExtraBold (800) para sa logo at mga heading
- Medium (500) para sa body text

```html
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,800&display=swap">
```

## Mga patakaran sa paggamit

- **Clear space:** mag-iwan ng espasyo sa paligid ng logo na kasing-laki ng isang tile.
- **Pinakamaliit na size:** 24px ang taas ng horizontal logo sa screen. Kapag mas maliit pa riyan, mark o favicon na lang ang gamitin.
- **Huwag:** i-stretch o i-distort, paikutin, palitan ang kulay ng tiles maliban sa mga bersyong kasama rito, lagyan ng shadow o gradient, o ibalik ang orange na tile sa loob ng grid (maliban sa favicon).
