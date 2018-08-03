<?php

use TowerUIX\Http\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Forget extends Controller {

    public function index() {

        if ($_POST) {
            $email = trim($_POST['email']);
            if ($email) {
                $knt2 = m_Users::find_by_email($email);
                if ($knt2) {

                    $_SESSION['login'] = true;
                    $_SESSION['uyeadi'] = "$knt2->uyeadi";
                    $_SESSION['id'] = "$knt2->id";
                    $_SESSION['emailadres'] = $email;
                    $_SESSION['kod'] = "$knt2->kod";


                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = 'ssl://smtp.gmail.com';
                    $mail->Port = 465;
                    $mail->Username = 'stajezgikaya@gmail.com';
                    $mail->Password = 'ezgikaya123';
                    $mail->SetFrom($mail->Username, 'Ezgi Kaya');
                    $mail->AddAddress($email, $_SESSION['uyeadi']);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Şifre Yenileme';
                    $mail->MsgHTML('<div style="font-family:Verdana,helvetica,Arial;"><p style="font-size:14px; color:black; font-weight: 600;">Sayın ' . $_SESSION['uyeadi'] . '</p>
        <p>Şifrenizi yeniden belirlemek için aşağıdaki bağlantıya tıklamanız yeterlidir. Lütfen şifrenizi kimseyle paylaşmayınız. </p><p> Güvenlik Kodunuz:&nbsp;' . $_SESSION['kod'] . '<p> Şifre Yenileme Bağlantısı:&nbsp;<a href="http://localhost/symfony/Refresh?kod='.md5($_SESSION['kod']). '">http://localhost/symfony/Refresh?kod=' .md5($_SESSION['kod']). '</a></p></div>');
                    if ($mail->Send()) {
                        $msg = '<div class="alert alert-success">
  <strong>Uyarı!</strong> Yeni Şifre Belirlemeniz İçin Kullanacağınız Link Mail Adresinize Başarıyla Gönderildi.
  </div>';
                    } else {
                        $msg = '<div class="alert alert-danger">
  <strong>Uyarı!</strong> Mail Gönderilirken Bir Hata Oluştu.
  </div>' . $mail->ErrorInfo;
                    }
                } else {
                    $msg = '<div class="alert alert-danger">
  <strong>Uyarı!</strong> Mail Adresi Bulunamadı.Lütfen tekrar deneyiniz.
  </div>';
                }
            }
        }
        $this->View("Forget", ['msg' => $msg]);
    }

}
