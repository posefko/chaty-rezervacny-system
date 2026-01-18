# CabinConnect – Rezervačný systém chát

Laravel 11 webová aplikácia pre správu chát a rezervácií.

## Funkcie
- Prehliadanie zoznamu chát a detailu chaty bez prihlásenia
- Dynamické filtrovanie chát bez obnovy stránky
- Oddelenie používateľských rolí (admin / user)
- Registrácia a bezpečné prihlásenie používateľov
- Vytváranie, úprava a rušenie rezervácií
- Automatická kontrola dostupnosti termínov
- Prehľad vlastných rezervácií pre prihláseného používateľa
- Administrátorská správa chát (vytváranie, úprava, mazanie)
- Responzívny dizajn

---

## Použité technológie

Backend: PHP 8.3, Laravel 11
Databáza: MySQL
Frontend: Blade templates, HTML5, CSS3, JavaScript (AJAX)
Styling: Tailwind CSS + vlastné CSS pravidlá
Vývojové nástroje: VS Code

---

## Požiadavky
- PHP 8.2+ (odporúčané 8.3)
- Composer
- Node.js + npm
- MySQL / MariaDB

---

## Inštalácia (lokálne)
1) Naklonovanie:
```bash
git clone https://github.com/posefko/chaty-rezervacny-system.git
cd chaty-rezervacny-system
```

2) PHP závislosti:
```bash
composer install
```

3) Frontend závislosti:
```bash
npm install
```

4) Konfigurácia:
```bash
cp .env.example .env
php artisan key:generate
```

5) Nastav databázu v `.env`:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chaty_rezervacny_system
DB_USERNAME=root
DB_PASSWORD=

6) Migrácie:
```bash
php artisan migrate
```

7) Storage link (kvôli uploadom obrázkov):
```bash
php artisan storage:link
```

---

## Spustenie
V 1. termináli:
```bash
npm run dev
```

V 2. termináli:
```bash
php artisan serve
```

Aplikácia beží na:
- http://127.0.0.1:8000

---

## Test rolí (admin/user)
Aplikácia používa middleware alias `admin` (registrovaný v `bootstrap/app.php`).

### Ako spraviť admina
Po registrácii používateľa nastav v DB v tabuľke `users` stĺpec `role` na hodnotu:
- `admin`

Bežný používateľ:
- `user`

---

## Poznámka k AI
Časti kódu vytvorené s pomocou AI sú označené priamo v komentároch v zdrojových súboroch.
