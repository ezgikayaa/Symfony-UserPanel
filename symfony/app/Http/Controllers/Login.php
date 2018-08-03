
<?php

use TowerUIX\Http\Controller;
use Gregwar\Captcha\CaptchaBuilder;

class Login extends Controller {

    public function index() {
        $builder = new CaptchaBuilder;
        if ($_POST) {
            $email = trim($_POST['email']);
            $pass = md5($_POST['sifre']);
            if (trim($_POST['kontrol']) == trim($_SESSION['phrase'])) {
                if ($email && $pass) {
                    $knt = m_Users::find_by_email_and_sifre($email, $pass);

                    if ($knt) {
                        $_SESSION['login'] = true;
                        $_SESSION['uyeadi'] = "$knt->uyeadi";
                        $_SESSION['id'] = "$knt->id";
                        $_SESSION['grupid'] = "$knt->grupid";
                        $_SESSION['emailadres'] = $email;
                        $_SESSION['şifre'] = $pass;


                        $action = m_Action::find_by_id('1');
                        $id = "$action->id";
                        $ad = "$action->ad";
                        $uyeadi = $_SESSION['uyeadi'];
                        $logtarih = strtotime('Y-m-d h:i:sa');
                        $ekle = m_Log::create([
                                    'uyeid' => $_SESSION['id'],
                                    'actionid' => $id,
                                    'text' => $ad,
                                    'log_olusturma_tarihi' => $logtarih
                        ]);
                        $ekle->save();

                        header("Location:" . url . "");
                    } else {
                        $msg = '<div class="alert alert-danger">
  <strong>Uyarı!</strong> Şifrenizi Yanlış Girdiniz.Lütfen tekrar deneyiniz.
  </div>';
                    }
                }
            } else {
                $msg = '<div class="alert alert-danger">
  <strong>Uyarı!</strong> Güvenlik Kodunuz Yanlış Girdiniz.Lütfen tekrar deneyiniz.
  </div>';
            }
        } else {
            
        }
        $builder->build();

        $_SESSION['phrase'] = $builder->getPhrase();
        $this->View("Login", ['captcha' => $builder->inline(), 'msg' => $msg]);
    }

    public function Logout() {
        $_SESSION['login'] = false;
        $_SESSION['emailadres'] = '';
        $_SESSION['şifre'] = '';

        $action = m_Action::find_by_id('5');
        $id = "$action->id";
        $ad = "$action->ad";
        $logtarih = strtotime('Y-m-d h:i:sa');
        $ekle = m_Log::create([
                    'uyeid' => $_SESSION['id'],
                    'actionid' => $id,
                    'text' => $ad,
                    'log_olusturma_tarihi' => $logtarih
        ]);
        $ekle->save();
        header("Location:" . url . "");
    }

}
