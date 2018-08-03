<?php

use TowerUIX\Http\Controller;

class Users extends Controller {

    public function __construct() {

        parent::__construct();
        if (!$_SESSION['login']) {
            header("Location:" . url . "Login");
            exit();
        }
        if (!$_SESSION['usergoruntuleyetki']) {
            header("Location:" . url . "Home");
            exit();
        }
    }

    public function index() {


        $Users = m_Users::all();
        // activerecord 
        foreach ($Users as $value) {
            $group = m_Groups::find_by_grupid($value->grupid);
            $DataUser[] = [
                'User_Name' => $value->uyeadi,
                'User_Email' => $value->email,
                'User_Sifre' => $value->sifre,
                'User_Tarih' => date("d.m.Y", strtotime($value->olusturma_tarihi)),
                'User_Grup' => $group->grupadi,
                'User_ID' => $value->id
            ];
        }

        $modulizin = m_Modül_İzinleri::find_by_modul_id_and_grup_id('1', $_SESSION['grupid']);
        $_SESSION['ekleyetki'] = $modulizin->ekle;
        $_SESSION['silyetki'] = $modulizin->sil;
        $_SESSION['duzenleyetki'] = $modulizin->duzenle;

        $isim = $_SESSION['uyeadi'];
        $this->View("Users", ['Users' => $DataUser, 'isim' => $isim,
            'EkleYetki' => $_SESSION['ekleyetki'],
            'SilYetki' => $_SESSION['silyetki'],
            'DuzenleYetki' => $_SESSION['duzenleyetki']]);
    }

    public function AddUsers() {

        $Groups = m_Groups::all();
        foreach ($Groups as $value) {
            $DataGroups[] = [
                'Group_Name' => $value->grupadi,
                'Group_ID' => $value->grupid,
            ];
        }
        if (!$_SESSION['ekleyetki']) {
            header("Location:" . url . "Users");
            exit();
        }

        if ($_POST) {

            $uyeid = $_SESSION['id'];
            $grupp = $_POST['grup'];
            $ad = trim($_POST['ad']);
            $email = trim($_POST['email']);
            $sifre = trim(md5($_POST['sifre']));
            $tarih = strtotime('Y-m-d h:i:sa');
            $karakterler = 'qwertyuopasdfghjklizxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
            $kod = substr(str_shuffle($karakterler), 0, 8);
            $ekle = m_Users::create([
                        'grupid' => $grupp,
                        'uye_olusturan_id' => $uyeid,
                        'uyeadi' => $ad,
                        'email' => $email,
                        'sifre' => $sifre,
                        'olusturma_tarihi' => $tarih,
                        'kod' => $kod
            ]);
            $ekle->save();

            $action = m_Action::find_by_id('2');
            $id = "$action->id";
            $add = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');

            $text = "$ad Kullanıcısını $add ";
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();
            sleep(1);
            header("Location:" . url . "/Users");
        }
        $isim = $_SESSION['uyeadi'];
        $this->View("AddUsers", ['Groups' => $DataGroups, 'isim' => $isim]);
    }

    public function DeleteUsers($id) {
        if (!$_SESSION['silyetki']) {
            header("Location:" . url . "Users");
            exit();
        }

        $sil = m_Users::find_by_id($id);

        $kontrol = $sil->delete();

        if ($kontrol) {
            $action = m_Action::find_by_id('4');
            $id = "$action->id";
            $add = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');
            $ad = "$sil->uyeadi";
            $text = "$ad Kullanıcısını $add ";
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();
            return true;
        } else {
            return false;
        }
    }

    public function UpdateUsers() {
        if (!$_SESSION['duzenleyetki']) {
            header("Location:" . url . "Users");
            exit();
        }
        $Groups = m_Groups::all();
        foreach ($Groups as $value) {
            $DataGroups[] = [
                'Group_Name' => $value->grupadi,
                'Group_ID' => $value->grupid,
            ];
        }

        $veri = m_Users::find_by_id($_GET['id']);

        $idd = "$veri->grupid";
        $adi = "$veri->uyeadi";
        $emailad = "$veri->email";

        if ($_POST) {
            $grupp = $_POST['grup'];
            $uyeadi = trim($_POST['ad']);
            $email = trim($_POST['email']);
            $uyetarihi = strtotime('Y-m-d h:i:sa');

            $ekle = m_Users::find_by_id($_GET['id']);
            $ekle->grupid = $grupp;
            $ekle->uyeadi = $uyeadi;
            $ekle->email = $email;
            if ($_POST['sifre']) {
                $sifre = md5($_POST['sifre']);
                $ekle->sifre = $sifre;
            }
            $ekle->guncelleme_tarihi = $uyetarihi;
            $ekle->save();
            $action = m_Action::find_by_id('3');
            $id = "$action->id";
            $add = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');

            if ($idd != $grupp && $adi != $uyeadi && $emailad != $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu, Adını,Email Adresini Ve Şifresini  $add ";
            } else if ($idd != $grupp && $adi != $uyeadi && $emailad != $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu,Adını Ve Email Adresini $add ";
            } else if ($idd != $grupp && $adi == $uyeadi && $emailad != $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu,Email Adresini Ve Şifresini $add ";
            } else if ($idd != $grupp && $adi != $uyeadi && $emailad == $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu,Adını Ve Şifresini $add ";
            } else if ($idd == $grupp && $adi != $uyeadi && $emailad != $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Adını,Email Adresini Ve Şifresini $add ";
            } else if ($idd != $grupp && $adi != $uyeadi && $emailad == $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu Ve Adını  $add ";
            } else if ($idd != $grupp && $adi == $uyeadi && $emailad == $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu Ve Şifresini $add ";
            } else if ($idd != $grupp && $adi == $uyeadi && $emailad != $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu  Ve Email Adresini $add ";
            } else if ($idd == $grupp && $adi == $uyeadi && $emailad != $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Email Adresini Ve Şifresini $add ";
            } else if ($idd == $grupp && $adi != $uyeadi && $emailad != $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Adını Ve Email Adresini $add ";
            } else if ($idd == $grupp && $adi != $uyeadi && $emailad == $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Adını Ve Şifresini $add ";
            } else if ($idd != $grupp && $adi == $uyeadi && $emailad == $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Grubunu $add ";
            } else if ($idd == $grupp && $adi != $uyeadi && $emailad == $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Adını $add ";
            } else if ($idd == $grupp && $adi == $uyeadi && $emailad != $email && empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Email Adresini $add ";
            } else if ($idd == $grupp && $adi == $uyeadi && $emailad == $email && !empty($sifre)) {
                $text = "$uyeadi Kullanıcısının Şifresini $add ";
            }
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();

            sleep(1);
            header("Location:" . url . "/Users");
        }
        $isim = $_SESSION['uyeadi'];
        $this->View("UpdateUsers", [
            'Groups' => $DataGroups,
            'Users' => $veri,
            'isim' => $isim,
        ]);
    }

}
