<?php

use TowerUIX\Http\Controller;

class Groups extends Controller {

    public function __construct() {

        parent::__construct();
        if (!$_SESSION['login']) {
            header("Location:" . url . "Login");
            exit();
        }
        if (!$_SESSION['grupgoruntuleyetki']) {
            header("Location:" . url . "Home");
            exit();
        }
    }

    public function index() {

        $Groups = m_Groups::all();

        foreach ($Groups as $value) {
            $users = m_Users::find_by_id($value->grup_olusturan_id);

            $DataGroups[] = [
                'Group_Name' => $value->grupadi,
                'Group_Olusturan_ID' => $users->uyeadi,
                'Group_ID' => $value->grupid,
                'Group_Tarih' => date("d.m.Y", strtotime($value->grup_olusturma_tarihi)),
            ];
        }


        $modulizin = m_Modül_İzinleri::find_by_modul_id_and_grup_id('2', $_SESSION['grupid']);
        $_SESSION['ekleyetki'] = $modulizin->ekle;
        $_SESSION['silyetki'] = $modulizin->sil;
        $_SESSION['duzenleyetki'] = $modulizin->duzenle;


        $isim = $_SESSION['uyeadi'];

        $this->View("Groups", ['Groups' => $DataGroups,
            'Kontrol' => $group,
            'EkleYetki' => $_SESSION['ekleyetki'],
            'SilYetki' => $_SESSION['silyetki'],
            'DuzenleYetki' => $_SESSION['duzenleyetki'],
            'isim' => $isim]);
    }

    public function AddGroups() {
        if (!$_SESSION['ekleyetki']) {
            header("Location:" . url . "Groups");
            exit();
        }

        if ($_POST) {

            $grupadi = trim($_POST['grupadi']);
            $gruptarih = strtotime('Y-m-d h:i:sa');
            $grupid = $_SESSION['id'];



            $ekle = m_Groups::create([
                        'grupadi' => $grupadi,
                        'grup_olusturan_id' => $grupid,
                        'grup_olusturma_tarihi' => $gruptarih
            ]);
            $ekle->save();

            $moduller = m_Modül::all();

            foreach ($moduller as $value) {
                $ekle3 = m_Modül_İzinleri::create([
                            'modul_id' => $value->id,
                            'grup_id' => $ekle->grupid,
                            'ekle' => $_POST['ekle'][$value->id] ? 1 : 0,
                            'sil' => $_POST['sil'][$value->id] ? 1 : 0,
                            'duzenle' => $_POST['duzenle'][$value->id] ? 1 : 0,
                            'goruntule' => $_POST['goruntule'][$value->id] ? 1 : 0,
                            "olusturan_id" => $_SESSION['id'],
                            'olusturma_tarihi' => $gruptarih
                ]);
                $ekle3->save();
            }


            $action = m_Action::find_by_id('2');
            $id = "$action->id";
            $ad = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');
            $text = "$grupadi Grubunu $ad ";
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();
            sleep(1);
            header("Location:" . url . "/Groups");
        }
        $isim = $_SESSION['uyeadi'];
        $this->View("AddGroups", ['isim' => $isim]);
    }

    public function DeleteGroups($id) {
        if (!$_SESSION['silyetki']) {
            header("Location:" . url . "Groups");
            exit();
        }

        $sil2 = m_Users::find_by_grupid($id);

        if ($sil2) {
            $kontrol2 = $sil2->delete();
            $moduller = m_Modül::all();
            foreach ($moduller as $value) {
                $sil5 = m_Modül_İzinleri::find_by_modul_id_and_grup_id($value->id, $id);
                $kontrol5 = $sil5->delete();
            }
            $sil = m_Groups::find_by_grupid($id);
            $kontrol = $sil->delete();
            $action = m_Action::find_by_id('4');
            $id = "$action->id";
            $ad = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');
            $grupadi = "$sil->grupadi";
            $text = "$grupadi Grubunu $ad ";
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();

            return true;
        } else {
            $moduller = m_Modül::all();
            foreach ($moduller as $value) {
                $sil4 = m_Modül_İzinleri::find_by_modul_id_and_grup_id($value->id, $id);
                $kontrol4 = $sil4->delete();
            }
            $sil3 = m_Groups::find_by_grupid($id);
            $kontrol3 = $sil3->delete();

            $action = m_Action::find_by_id('4');
            $id = "$action->id";
            $ad = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');
            $grupadi = "$sil3->grupadi";
            $text = "$grupadi Grubunu $ad ";
            $ekle3 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle3->save();


            return true;
        }
    }

    public function UpdateGroups() {

        if (!$_SESSION['duzenleyetki']) {
            header("Location:" . url . "Groups");
            exit();
        }

        $veri = m_Groups::find_by_grupid($_GET['id']);

        $modulizin = m_Modül_İzinleri::all();

        foreach ($modulizin as $value) {

            if ($value->grup_id == $veri->grupid) {

                $ModulIzinleri[$value->modul_id][] = [
                    'Ekle' => $value->ekle,
                    'Sil' => $value->sil,
                    'Duzenle' => $value->duzenle,
                    'Goruntule' => $value->goruntule,
                    'Modulid' => $value->modul_id,
                ];
            }
        }

        if ($_POST) {
            $grupadi = trim($_POST['grupadi']);
            $gruptarihi = strtotime('Y-m-d h:i:sa');

            $ekle = m_Groups::find_by_grupid($_GET['id']);


            $ekle->update_attributes(array('grupadi' => $grupadi,
                'grup_guncelleme_tarihi' => $gruptarihi));

            $moduller = m_Modül::all();


            foreach ($moduller as $value) {
                $sil = m_Modül_İzinleri::find_by_modul_id_and_grup_id($value->id, $_GET['id']);
                if ($sil) {
                    $sil->delete();

                    $ekle3 = m_Modül_İzinleri::create([
                                'modul_id' => $value->id,
                                'grup_id' => $_GET['id'],
                                'ekle' => $_POST['ekle'][$value->id] ? 1 : 0,
                                'sil' => $_POST['sil'][$value->id] ? 1 : 0,
                                'duzenle' => $_POST['duzenle'][$value->id] ? 1 : 0,
                                'goruntule' => $_POST['goruntule'][$value->id] ? 1 : 0,
                                "olusturan_id" => $_SESSION['id'],
                                'olusturma_tarihi' => $logtarih
                    ]);
                } else {
                    $ekle3 = m_Modül_İzinleri::create([
                                'modul_id' => $value->id,
                                'grup_id' => $_GET['id'],
                                'ekle' => $_POST['ekle'][$value->id] ? 1 : 0,
                                'sil' => $_POST['sil'][$value->id] ? 1 : 0,
                                'duzenle' => $_POST['duzenle'][$value->id] ? 1 : 0,
                                'goruntule' => $_POST['goruntule'][$value->id] ? 1 : 0,
                                "olusturan_id" => $_SESSION['id'],
                                'olusturma_tarihi' => $logtarih
                    ]);
                }
            }
            $ekle3->save();


            $action = m_Action::find_by_id('3');
            $id = "$action->id";
            $ad = "$action->ad";
            $logtarih = strtotime('Y-m-d h:i:sa');
            $text = "$grupadi Grubunu $ad ";
            $ekle2 = m_Log::create([
                        'uyeid' => $_SESSION['id'],
                        'actionid' => $id,
                        'text' => $text,
                        'log_olusturma_tarihi' => $logtarih
            ]);
            $ekle2->save();
            sleep(1);
            header("Location:" . url . "/Groups");
        }

        $isim = $_SESSION['uyeadi'];
        $this->View("UpdateGroups", ['GrupVeri' => $veri, 'isim' => $isim, 'ModulIzinleri' => $ModulIzinleri]);
    }

}
