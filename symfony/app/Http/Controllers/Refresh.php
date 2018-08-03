<?php

use TowerUIX\Http\Controller;

class Refresh extends Controller {

    public function index() {

        if ($_POST) {

            $sifre = trim(md5($_POST['sifre']));
            $ekle = m_Users::find_by_email($_SESSION['emailadres']);
            $kod = "$ekle->kod";
            if ($kod == $_POST['kod']) {
                $karakterler = 'qwertyuopasdfghjklizxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
                $kod = substr(str_shuffle($karakterler), 0, 8);

                $ekle->sifre = $sifre;
                $ekle->kod = $kod;
                $ekle->save();
                $action = m_Action::find_by_id('6');
                $id = "$action->id";
                $add = "$action->ad";
                $logtarih = strtotime('Y-m-d h:i:sa');
                $uyeid = "$ekle->id";
                $text = "$ad Şifresini $add ";
                $ekle2 = m_Log::create([
                            'uyeid' => $uyeid,
                            'actionid' => $id,
                            'text' => $text,
                            'log_olusturma_tarihi' => $logtarih
                ]);
                $ekle2->save();
                sleep(1);

                header("Location:" . url . "/Login");
            } else {

                $msg = '<div class="alert alert-danger">
  <strong>Uyarı!</strong> Güvenlik Kodunuzu Yanlış Girdiniz.Lütfen tekrar deneyiniz.
  </div>';
            }
        }
        $this->View("Refresh", ['msg' => $msg]);
    }

}
