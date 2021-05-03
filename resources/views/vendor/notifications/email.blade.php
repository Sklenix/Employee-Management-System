<!-- Nazev souboru: email.blade.php -->
<!-- Prepsani puvodniho zneni emailove zpravy pri zazadani o obnovu hesla (urcene pro uzivatele s roli firmy), ktera se nachazi v autorizacnim a autentifikacnim balicku frameworku Laravel -->
<html>
    <title>Resetování hesla</title>
    <body style="background-color: #333">
    <div class="content">
            <div style="padding-top:50px;"></div>
            <div class="informace" style="background-color: #f8f9fa;margin-bottom:15px;margin-left:40px;margin-right:40px;padding:30px">
                <p style="color:black;text-align: center;font-size: 14px;">Dobrý den,</p>
                <p style="color:black;text-align: center;font-size: 13px;">Posíláme Vám tento email, protože jste zažádal o změnu hesla, pokud tomu tak nebylo, pak tento email ignorujte.</p>
                <center>
                @component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
                    {{$actionText = "Resetovat heslo"}}
                @endcomponent
                </center>
                <p style="color:black;text-align: center;font-size: 11px;">Tento resetovací link bude funkční po dobu 60 minut,</p>
                <p style="color:black;text-align: center;font-size: 14px;">Tozondo</p>
                <p style="color:black;text-align: center;font-size:11px;">Pokud máte problém rozkliknout tlačítko, vložte níže uvedené URL do prohlížeče.</p>
                <center><div class="break-all" >({{ $actionUrl }})</div></center>
            </div>
            <div style="padding-bottom:50px;"></div>
        </div>
    </body>
</html>
