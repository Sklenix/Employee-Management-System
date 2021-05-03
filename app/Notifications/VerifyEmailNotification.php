<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;


class VerifyEmailNotification extends VerifyEmail {
    /* Tato trida slouzi k prepsani puvodni notifikace k odesilani verifikacnich emailovych zprav (zabudovane v Laravel autentizacnim a autorizacnim balicku) tak,
    aby byla notifikace v ceskem jazyce, jedna se o prepsani souboru VerifyEmail.php, autor uprav: Pavel Sklenář (xsklen12) */

    /* Nastaveni jakou formou se ma notifikace odeslat */
    public function via($urlOvereni){
        return ['mail'];
    }

    /* Odeslani notifikace */
    public function toMail($urlOvereni){
        return $this->buildMailMessage($urlOvereni);
    }

    /* Definice emailove zpravy*/
    protected function buildMailMessage($urlOvereni){
        return (new MailMessage)
            ->subject(Lang::get('Ověření emailové adresy'))
            ->view('emails_company_verify', ['url' => $this->verificationUrl($urlOvereni)]);
    }
}
