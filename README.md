## Informační systém pro řízení zaměstnanců firmy

Webová aplikace implementovaná v jazyce PHP s využitím frameworku Laravel společně s MySQL databází. 
Mezi hlavní funkce systému patří:
- správa zaměstnanců, včetně správy jejich docházek, 
- práce s Google Drive diskem,
- generování různých souborů ve formátu PDF,
- zobrazení různých statistik, hodnocení zaměstnanců,
- správa směn, 
- správa zaměstnaneckých jazyků, 
- správa dovolených, nemocenských a nahlášení zaměstnanců.

## Použitý framework
- [Laravel](https://laravel.com/)

## Použité knihovny
- [jQuery](https://jquery.com/)
- [Yajra Datatables](https://yajrabox.com/docs/laravel-datatables/master/installation)
- [Google Drive API](https://developers.google.com/drive/api/v3/quickstart/php)
- [Chart.js](https://www.chartjs.org/)
- [DOMPDF Wrapper for Laravel](https://github.com/barryvdh/laravel-dompdf)
- [Chart.js Doughnutlabel plugin](https://github.com/ciprianciurea/chartjs-plugin-doughnutlabel)
- [Chart.js Datalabels plugin](https://github.com/chartjs/chartjs-plugin-datalabels)
- [Bootstrap](https://getbootstrap.com/)
- [DataTables](https://datatables.net/)
- [Moment.js](https://momentjs.com/)
- [Datetimepicker](https://github.com/xdan/datetimepicker/blob/master/MIT-LICENSE.txt)
- [Modernizr](https://modernizr.com/)

### Použité programovací jazyky
- [PHP](https://www.php.net/)
- [Javascript](https://www.javascript.com/)

### Použité jazyky pro definici struktury a vzhledu webových stránek
- [HTML](https://www.w3schools.com/html/html_intro.asp)
- [CSS](https://developer.mozilla.org/en-US/docs/Web/CSS)

### Použité fonty
- [Roboto](https://fonts.google.com/specimen/Roboto)
- [Pacifico](https://fonts.google.com/specimen/Pacifico)
- [Nunito](https://fonts.google.com/specimen/Nunito)

## Instalace
### Prerekvizity
1. Nainstalované libovolné vývojové prostředí pro tvorbu webových aplikací, které obsahuje MySQL databázi a jazyk PHP,  
   například [WAMP Server](https://www.wampserver.com/en/)
2. Nastavit jazyk PHP jako systémovou proměnnou, poté bude možné ovládat jazyk PHP z příkazové řádky,
   [návod zde.](https://www.forevolve.com/en/articles/2016/10/27/how-to-add-your-php-runtime-directory-to-your-windows-10-path-environment-variable/)
### Proces
1. Pro tvorbu nové databáze je potřeba spustit samotný WAMP Server a přejít na adresu **http://127.0.0.1/**.  Na této adrese se vyskytuje webová stránka, která obsahuje, v sekci  **Tools**, nástroj phpMyAdmin. 
   V nástroji phpMyAdmin je potřeba kliknout na možnost *Databáze*, která se nachází v menu,  a následně vyplnit název a kódování databáze a stisknout tlačítko *Vytvořit*. V rámci kódování vyberte **utf8mb4_unicode_ci**.
2. V souboru **.env**, který se nachází ve složce tozondo, vyplňte údaje potřebné k připojení k databázi, která byla vytvořena v nástroji phpMyAdmin. Údaje pro mailový server ponechte beze změn.  Údaje potřebné pro připojení 
   k databázi jsou konkrétně následující:
-  *DB_DATABASE* - název databáze vytvořené v phpMyAdmin,
-  *DB_USERNAME* - uživatelské jméno k nástroji phpMyAdmin (defaultně root),
- *DB_PASSWORD* - heslo k phpMyAdmin (defaultně bez hesla).
3. Spusťte příkazovou řádku a dostaňte se v ní do složky tozondo, v které se nachází soubor **artisan**.
4. Zadejte příkaz **php artisan migrate:fresh** pro vytvoření tabulek a následovně **php artisan db:seed** pro   
   naplnění tabulek připravenými záznamy.
5. Nyní stačí zadat příkaz **php artisan serve** a přejít na stránku **http://127.0.0.1:8000/** (musí být spuštěný WAMP Server).
### Poznámka
Pro nahrávání objemnějších souborů, v rámci Google Drive, je zapotřebí v souboru **php.ini**, který se nachází ve složce WAMP Serveru, přenastavit proměnné **post_max_size** a **upload_max_size** na příslušnou požadovanou hodnotu, například 100 MB.

Ukázka cesty k **php.ini** souboru: C:\wamp64\bin\php\php7.4.9\php.ini

## Licence
- Framework Laravel je open source software, který je distribuován pod licencí [MIT](https://opensource.org/licenses/MIT).
- Yajra Datatables je knihovna, která je distribuovaná pod licencí [MIT](https://github.com/yajra/laravel-datatables/blob/9.0/LICENSE.md).
- Google Drive API je knihovna, která je distribuovaná pod licencí [Apache License 2.0](https://github.com/googleapis/google-api-php-client/blob/master/LICENSE)
- Chart.js je knihovna, která je distribuovaná pod licencí [MIT](https://github.com/chartjs/Chart.js/blob/master/LICENSE.md).
- DOMPDF Wrapper for Laravel je knihovna, která je distribuovaná pod licencí [MIT](https://opensource.org/licenses/MIT).
- Chart.js Datalabels plugin je plugin, který je distribuován pod licencí [MIT](https://github.com/chartjs/chartjs-plugin-datalabels/blob/master/LICENSE.md).
- Chart.js Doughnutlabel plugin je plugin, který je distribuován pod licencí [MIT](https://github.com/ciprianciurea/chartjs-plugin-doughnutlabel/blob/master/LICENSE).
- Bootstrap je knihovna, která je distribuovaná pod licencí [MIT](https://github.com/twbs/bootstrap/blob/main/LICENSE)
- DataTables je open source software, který je od verze 1.10 distribuovaný pod licencí [MIT](https://datatables.net/license/)
- Moment.js je knihovna, která je distribuovaná pod licencí [MIT](https://github.com/moment/momentjs.com/blob/master/LICENSE)
- Datetimepicker je plugin, který je distribuován pod licencí [MIT](https://github.com/xdan/datetimepicker/blob/master/MIT-LICENSE.txt)
- Modernizr je knihovna, která je distribuovaná pod licencí [MIT](https://github.com/Modernizr/Modernizr/blob/master/LICENSE.md)
- jQuery je knihovna, která je distribuovaná pod licencí [MIT](https://jquery.org/license/).

Jednotlivé licence jsou obsaženy v souboru LICENSE.md

