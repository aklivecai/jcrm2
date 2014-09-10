<?php
class SysCrypt {
    private $crypt_key = 'http://www.tak.com'; //密钥
    public function __construct($crypt_key = 'tak') {
        $this->crypt_key = $crypt_key;
    }
    private $reps = array(
        '=' => '_9JUREN',
        '/' => 'TAKS',
        '\\' => '_ERP',
        '+' => '_CRM',
        '|' => '_0755',
        '.' => '_0779',

    );
    private function rep($str, $get = false) {
        if ($get) {
            $reps = array_flip($this->reps);
        } else {
            $reps = $this->reps;
        }
        foreach ($reps as $key => $value) {
            $str = str_replace($key, $value, $str);
        }
        return $str;
    }
    public function encrypt($txt) {
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for ($i = 0;$i < strlen($txt);$i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp.= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        $txt = base64_encode(self::__key($tmp, $this->crypt_key));
        // $txt = urlencode($txt);
        $txt = $this->rep($txt);
        return $txt;
    }
    public function decrypt($txt) {
        // $txt = urldecode($txt);
        $txt = $this->rep($txt, true);
        $txt = self::__key(base64_decode($txt) , $this->crypt_key);
        $tmp = '';
        for ($i = 0;$i < strlen($txt);$i++) {
            $md5 = $txt[$i];
            $tmp.= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }
    private function __key($txt, $encrypt_key) {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0;$i < strlen($txt);$i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp.= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
    public function __destruct() {
        $this->crypt_key = NULL;
    }
}
// $m = new SysCrypt();
// $t = $m->encrypt('53788158634402140');
// $t2 = $m->decrypt($t);

// echo sprintf("%s\n", $t);
// echo sprintf("%s\n", $t2);
// echo sprintf("%s\n", $m->encrypt('0'));
// echo sprintf("%s\n",$m->decrypt('C2NVZQE3DH8Eew=='));

// $t2 = $m->decrypt('UmwGYQNmDGkAbVs0CWUAaw9mVGBYZQE0AjYAMAIwVjJQaw==');
// echo $t2;

// echo sprintf("%s\n%s%s", 1,2);
