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
- [Laravel IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper)

## Instalace
### Prerekvizity
1. Nainstalovaný nástroj [Composer](https://getcomposer.org/)
2. Nainstalované libovolné vývojové prostředí pro tvorbu webových aplikací, které obsahuje MySQL databázi a PHP,
   například [WAMP Server](https://www.wampserver.com/en/)
3. Nastavit jazyk PHP jako systémovou proměnnou, aby se dal ovládat z příkazové řádky (cmd) 
### Proces
1. Vytvořte soubor **.env**, následně do něj zkopírujte obsah souboru **.env.example**. V souboru **.env** vyplňte 
údaje potřebné k připojení k databázi.
2. V kořenovém adresáři spustťe příkaz **composer install** (přes cmd).
3. Vygenerujte klíč aplikace pomocí příkazu **php artisan key:generate**
4. Zadejte příkaz **php artisan migrate:fresh** pro vytvoření tabulek a následovně **php artisan db:seed** pro 
naplnění tabulek záznamy.
5. Nyní stačí spustit databázi MySQL (WAMP Server) a zadat příkaz **php artisan serve** a přejít na stránku **http://127.0.0.1:8000/**.

### Poznámka
Pro nahrávání objemnějších souborů je zapotřebí v souboru php.ini přenastavit proměnné post_max_size a upload_max_size na příslušnou požadovanou hodnotu, například 100 MB.

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
- Laravel IDE Helper Generator je knihovna, která je distribuovaná pod licencí [MIT](https://opensource.org/licenses/MIT)

Laravel IDE Helper Generator byl použit pro generování komentářů pro automatické doplňování v rámci IDE. Jedná se například o doplňování atributů tabulek, metod v rámci modelů, ... 

Jednotlivé licence jsou obsaženy v souboru LICENSE.md

