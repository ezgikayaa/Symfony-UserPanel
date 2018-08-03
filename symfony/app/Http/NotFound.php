<?php

use TowerUIX\Http\Controller;

class NotFound extends Controller {

    public function index($param=""){
        if($param){

            $FindPageSlug = m_Slug::find_by_modul_id_and_permant_and_varsayilan(2, $param,1);

            $FindPage     = m_Sayfa::find_by_uid_and_deleted_and_publishing($FindPageSlug->x_id, 0, 1);
            if($FindPage){



                $etiket_rell = \m_Etiket_rel::find_all_by_modul_id_and_x_id(2, $FindPage->id);

                $etiketler = "";
                foreach ($etiket_rell as $value) {
                    $etiket = m_Etiketler::find_by_id($value->etiket_id);
                    $etiketler = $etiketler . $etiket->ad . ",";
                }

                $ContentReplate = str_replace('../uploads', url . 'uploads', htmlspecialchars_decode($FindPage->icerik, ENT_QUOTES));

                $ban = \m_Resim::find_by_modul_id_and_x_id(2, $FindPage->uid);

                $PageData = [
                    'title' => $FindPage->baslik,
                    'content' => $ContentReplate,
                    'etiket' => @$etiketler,
                    'resim' => $ban->dosya_adi ,
                ];




                return  $this->View('Page', ['page' => $PageData,]);
            }else {


                // Eğer Gönderilen Slug Bir Sayfa Değilse TurKategorisi mi diye kontrol ediyoruz.
                // Eğer TurKategorisi var ise TurListe Sayfasına yönlendireceğiz.
                // TurKategorisi yok ise Tur var mı Diye Kontrol Edeceğiz.
                $FindTurSlug = m_Slug::find_by_modul_id_and_permant_and_varsayilan(4, $param, 1);
                $FindTurKat = m_TurKategorileri::find_by_uid_and_deleted_and_publishing($FindTurSlug->x_id, 0, 1);
                if ($FindTurKat) {
                    $Helpers = new \TowerUIX\Src\Helpers();
                    $Iliskiler = m_Iliskiler::find_all_by_x_modul_id_and_y_modul_id_and_y_id(3,4,$FindTurKat->uid);
                    foreach ($Iliskiler as $item) {

                        $IliskiPlan = m_Iliskiler::find_all_by_x_modul_id_and_y_modul_id_and_y_id(5, 3, $item->x_id);

                        foreach ($IliskiPlan as $val) {
                            $Tur = $Helpers->TurDetay($val->x_id);
                            if ($Tur) {
                                $TurPlanlari = \m_TurPlanlari::find_by_uid_and_dil_id_and_deleted_and_publishing($val->x_id, dil_id, 0, 1);
                                $Tarih = strtotime(date('Y-m-d H:i:s'));
                                $BaslangicTarihi = strtotime($TurPlanlari->baslangic_tarihi);

                                if($BaslangicTarihi > $Tarih){
                                    $status = false;
                                }else{
                                    $status = true;
                                }

                                if($TurPlanlari->dolu)
                                {
                                    $status =true;
                                }


                                $Data[] = [
                                    'tarih' => strtotime($TurPlanlari->baslangic_tarihi),
                                    'Status' => $status,
                                    'Tur' => $Helpers->TurDetay($val->x_id),
                                ];
                            }

                        }







                    }
                       $TurKategorileri = m_TurKategorileri::find_all_by_deleted_and_publishing_and_dil_id(0,1,dil_id,array('order' => 'sira asc'));
                        foreach ($TurKategorileri as $value) {
                            $DataKategori[]=[
                                'Ad' => $value->ad,
                                'Permant' => $Helpers->GetPermant(4,$value->uid),
                                'active' => $param == $Helpers->GetPermant(4,$value->uid) ? 'active' : ''
                            ];
                        }
                        array_multisort($Data, SORT_ASC, $Data);


                    $this->View('TurListe', [
                        'TurPlanlari' => $Data,
                        'Kategoriler' => $DataKategori,
                        'TurKatAdi' =>$FindTurKat->ad,
                        'Banner' => $Helpers->GetImage(4,$FindTurKat->uid),
                        'Gorusler' => $Helpers->MusteriGorusleri(),
                    ]);
                } else {
                    // Eğer Gönderilen Slug Bir Sayfa Değilse Tur mu diye kontrol ediyoruz.
                    // Eğer Tur var ise TurDetay Sayfasına yönlendireceğiz.
                    // Tur yok ise 404 sayfasına yönlendirme yapacağız.
                    $FindTurSlug = m_Slug::find_by_modul_id_and_permant_and_varsayilan(5, $param, 1);
                    $FindTur = m_TurPlanlari::find_by_uid_and_deleted_and_publishing($FindTurSlug->x_id, 0, 1);
                    if ($FindTur) {
                        $Helpers = new \TowerUIX\Src\Helpers();
                        $Bugun = strtotime(date('Y-m-d'));
                        $Baslangic = strtotime($FindTur->baslangic_tarihi);

                        if($Bugun < $Baslangic)
                        {
                            if($FindTur->dolu)
                            {
                                $rezervasyon = false;

                            }else{
                                $rezervasyon=true;
                            }
                       }else{
                         $rezervasyon = false;
                       }




                        $this->View('TurDetay', [
                          'Tur' => $Helpers->TurDetay($FindTurSlug->x_id),
                          'Gorusler' => $Helpers->MusteriGorusleri(),
                          'Rezervasyon' => $rezervasyon,
                        ]);
                    } else {
                        $this->View('404');
                    }
                }
            }
        }else{
            $this->View('404');
        }
    }
}
