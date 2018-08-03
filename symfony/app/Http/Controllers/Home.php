<?php

use TowerUIX\Http\Controller;

class Home extends Controller {

    public function __construct() {

        parent::__construct();
        if (!$_SESSION['login']) {
            header("Location:" . url . "Login");
            exit();
        }
    }

    public function index() {
        $Log = m_Log::find('all', array('order' => 'log_olusturma_tarihi desc'));

        foreach ($Log as $value) {
            $users = m_Users::find_by_id($value->uyeid);
            $actions = m_Action::find_by_id($value->actionid);

            $DataLogs[] = [
                'Log_UyeAdi' => $users->uyeadi,
                'Log_Action' => $actions->icon,
                'Log_ActionClass' => $actions->class,
                'Log_Mesaj' => $value->text,
                'Log_Tarih' => date("[d.m.Y H:i:s]", strtotime($value->log_olusturma_tarihi)),
            ];
            $dizi[] = date("[d.m.Y H:i:s]", strtotime($value->log_olusturma_tarihi));
        }
        $tarih = date("d.m.Y");
        for ($i = 0; $i <= count($dizi); $i++) {
            $dizi2[$i] = substr($dizi[$i], 1, 11);
            if (strtotime($dizi2[$i]) == strtotime($tarih)) {
                $tarihdizi[$i] = $dizi[$i];
            }
        }

        foreach ($tarihdizi as $dizi3) {
            $Tarih[] = ['Tarih' => $dizi3];
        }

        $isim = $_SESSION['uyeadi'];
        $this->View("Home", ['Log' => $DataLogs, 'Tarih' => $Tarih, 'isim' => $isim]);
    }

}
