<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuestionWelcomeMail extends Mailable {
    /* Nazev souboru: SendQuestionWelcomeMail.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato třída slouzi k definici odesilanych emailovych zprav v ramci formulare na uvodni strane informacniho systemu */

    use Queueable, SerializesModels;
    /* Tridni promenna pro ulozeni udaju, ktere se maji poslat */
    public $udaje;

    /* Vytvoreni instance nove emailove zpravy */
    public function __construct($udaje) {
        $this->udaje = $udaje;
    }

    /* Vytvoreni emailove zpravy */
    public function build() {
        return $this->from($this->udaje['email'])
            ->subject('Dotaz z Tozondo.com')
            ->view('email_welcome')->with('udaje', $this->udaje); // pouziti view pro text emailu
    }
}
