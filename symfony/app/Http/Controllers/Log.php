<?php

use TowerUIX\Http\Controller;

class Log extends Controller {

    public function __construct() {

        parent::__construct();
        if (!$_SESSION['login']) {
            header("Location:" . url . "Login");
            exit();
        }
        if (!$_SESSION['loggoruntuleyetki']) {
            header("Location:" . url . "Home");
            exit();
        }
    }

    public function index() {

        $Log = m_Log::find('all', array('order' => 'log_olusturma_tarihi desc'));

        foreach ($Log as $value) {
            $users = m_Users::find_by_id($value->uyeid);
            $actions = m_Action::find_by_id($value->actionid);
            $DataLog[] = [
                'Log_UyeAdi' => $users->uyeadi,
                'Log_Action' => $actions->icon,
                'Log_ActionClass' => $actions->class,
                'Log_Mesaj' => $value->text,
                'Log_Tarih' => date("[d.m.Y H:i:s]", strtotime($value->log_olusturma_tarihi)),
            ];
        }

        $Groups = m_Groups::all();
        foreach ($Groups as $value) {
            $DataGroups[] = [
                'Group_Name' => $value->grupadi,
            ];
        }

        $isim = $_SESSION['uyeadi'];
        $this->View("Log", ['Logs' => $DataLog, 'isim' => $isim, 'Groups' => $DataGroups]);
    }

    public function Arama() {
        $Groups = m_Groups::all();
        foreach ($Groups as $value) {
            $DataGroups[] = [
                'Group_Name' => $value->grupadi,
            ];
        }
        if ($_POST) {
            $bastarih = $_POST['bastarih'];
            $bittarih = $_POST['bittarih'];
            $bastarih2 = date("Y-m-d", strtotime($_POST['bastarih']));
            $bittarih2 = date("Y-m-d", strtotime($_POST['bittarih']));
            $grupadi = $_POST['grupadi'];

            if ($bastarih && $bittarih && $grupadi) {
                $ara = m_Log::find('all', array('conditions' => array("DATE(log_olusturma_tarihi) BETWEEN '$bastarih2' AND '$bittarih2' AND text LIKE '%$grupadi%'")));
                foreach ($ara as $value) {
                    $users = m_Users::find_by_id($value->uyeid);
                    $actions = m_Action::find_by_id($value->actionid);
                    $AraLog[] = [
                        'Log_UyeAdi' => $users->uyeadi,
                        'Log_Action' => $actions->icon,
                        'Log_ActionClass' => $actions->class,
                        'Log_Mesaj' => $value->text,
                        'Log_Tarih' => date("[d.m.Y H:i:s]", strtotime($value->log_olusturma_tarihi)),
                    ];
                }
                $grupadi2 = $_POST['grupadi'];
            } else if (!$bastarih && !$bittarih && $grupadi) {
                $ara = m_Log::find('all', array('conditions' => array("text LIKE '%$grupadi%'")));

                foreach ($ara as $value) {
                    $users = m_Users::find_by_id($value->uyeid);
                    $actions = m_Action::find_by_id($value->actionid);
                    $AraLog[] = [
                        'Log_UyeAdi' => $users->uyeadi,
                        'Log_Action' => $actions->icon,
                        'Log_ActionClass' => $actions->class,
                        'Log_Mesaj' => $value->text,
                        'Log_Tarih' => date("d.m.Y H:i:s", strtotime($value->log_olusturma_tarihi)),
                    ];
                }
                $grupadi2 = $_POST['grupadi'];
            } else if ($bastarih && $bittarih && !$grupadi) {
                $ara = m_Log::find('all', array('conditions' => array("DATE(log_olusturma_tarihi) BETWEEN '$bastarih2' AND '$bittarih2'")));

                foreach ($ara as $value) {
                    $users = m_Users::find_by_id($value->uyeid);
                    $actions = m_Action::find_by_id($value->actionid);
                    $AraLog[] = [
                        'Log_UyeAdi' => $users->uyeadi,
                        'Log_Action' => $actions->icon,
                        'Log_ActionClass' => $actions->class,
                        'Log_Mesaj' => $value->text,
                        'Log_Tarih' => date("[d.m.Y H:i:s]", strtotime($value->log_olusturma_tarihi)),
                    ];
                }
            }
        }


        $isim = $_SESSION['uyeadi'];
        $this->View("Log", ['Logs' => $AraLog, 'GrupAra' => $GrupAra, 'isim' => $isim, 'grupadi2' => $grupadi2, 'bastarih' => $bastarih, 'bittarih' => $bittarih, 'Groups' => $DataGroups]);
    }

}
