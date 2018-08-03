<?php
namespace TowerUIX\Src;
class Helpers
{

    public function GetPage($param)
    {
        $FindPageSlug = \m_Slug::find_by_modul_id_and_permant(9, $param);
        $FindPage = \m_Sayfa::find_by_id_and_deleted_and_publishing($FindPageSlug->x_id, 0, 1);
        $ban = \m_Resim::find_by_modul_id_and_x_id(9, $FindPage->uid);
        $ContentReplate = str_replace('../uploads', url . 'uploads', htmlspecialchars_decode($FindPage->icerik, ENT_QUOTES));

        $PageData = [
            'title' => $FindPage->baslik,
            'content' => $ContentReplate,
            'etiket' => $this->GetTags(9, $FindPageSlug->x_id),
            "resim" => $ban->dosya_adi,
        ];

        return $PageData;

    }

    public function GetTags($ModulID, $x_id)
    {
        $etiket_rell = \m_Etiket_rel::find_all_by_modul_id_and_x_id($ModulID, $x_id);
        $etiketler = "";
        foreach ($etiket_rell as $value) {
            $etiket = \m_Etiketler::find_by_id($value->etiket_id);
            $etiketler = $etiketler . $etiket->ad . ",";
        }

        return $etiketler;
    }

    public function GetTagsArray($ModulID, $x_id)
    {
        $etiket_rell = \m_Etiket_rel::find_all_by_modul_id_and_x_id($ModulID, $x_id);
        foreach ($etiket_rell as $value) {
            $etiket = \m_Etiketler::find_by_id($value->etiket_id);
            $etiketler[] = [
                "Etiket" => $etiket->ad,
            ];
        }


        return $etiketler;
    }

    public function GetImage($ModulID, $ID, $TipID = '')
    {
        if ($TipID) {
            $ban = \m_Resim::find_by_modul_id_and_x_id_and_tip_id($ModulID, $ID, $TipID);
        } else {


            $ban = \m_Resim::find_by_modul_id_and_x_id($ModulID, $ID);
        }

        return @$ban->dosya_adi ? $ban->dosya_adi : 'bos.jpg';
    }

    public function GetImageArray($ModulID, $ID, $TipID = '')
    {
        if ($TipID) {
            $ban = \m_Resim::find_all_by_modul_id_and_x_id_and_tip_id($ModulID, $ID, $TipID, array('order' => 'sira asc'));
        } else {
            $ban = \m_Resim::find_all_by_modul_id_and_x_id($ModulID, $ID, array('order' => 'sira asc'));
        }
        foreach ($ban as $item) {
            $Return[] = [
                'dosya_adi' => $item->dosya_adi ? $item->dosya_adi : 'bos.jpg'
            ];
        }

        return $Return;
    }

    public function GetPermant($ModulID, $ID)
    {
        $permant = \m_Slug::find_by_modul_id_and_x_id_and_varsayilan_and_dil_id($ModulID, $ID, 1, dil_id);
        return $permant->permant;
    }

 
}
