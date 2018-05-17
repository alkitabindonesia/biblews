<?php
/*
 * Dikembangkan oleh Budi Susanto (budsus@ti.ukdw.ac.id)
 * Awal Pengembangan: 16 Desember 2016
 *
 */
class AlkitabController extends AppController {
  public $components = array('RequestHandler');
  private static $kamusKitab = array(
      'kej' => 'kejadian',
      'kel' => 'keluaran',
      'im' => 'imamat',
      'bil' => 'bilangan',
      'ul' => 'ulangan',
      'yos' => 'yosua',
      'hak' => 'hakim-hakim',
      'rut' => 'rut',
      '1sam' => '1samuel',
      '2sam' => '2samuel',
      '1raj' => '1raja-raja',
      '2raj' => '2raja-raja',
      '1taw' => '1tawarikh',
      '2taw' => '2tawarikh',
      'ezr' => 'ezra',
      'neh' => 'nehemia',
      'est' => 'ester',
      'ayb' => 'ayub',
      'mzm' => 'mazmur',
      'ams' => 'amsal',
      'pkh' => 'pengkotbah',
      'kid' => 'kidung agung',
      'yes' => 'yesaya',
      'yer' => 'yeremia',
      'rat' => 'ratapan',
      'yeh' => 'yehezkiel',
      'dan' => 'daniel',
      'hos' => 'hosea',
      'yl' => 'yoel',
      'am' => 'amos',
      'ob' => 'obaja',
      'yun' => 'yunus',
      'mi' => 'mikha',
      'nah' => 'nahum',
      'hab' => 'habakuk',
      'zef' => 'zehanya',
      'hag' => 'hagai',
      'za' => 'zakharia',
      'mal' => 'maleakhi',
      'mat' => 'matius',
      'mrk' => 'markus',
      'luk' => 'lukas',
      'yoh' => 'yohanes',
      'kis' => 'kisah para rasul',
      'rm' => 'roma',
      '1kor' => '1korintus',
      '2kor' => '2korintus',
      'gal' => 'galatia',
      'ef' => 'efesus',
      'flp' => 'filipi',
      'kol' => 'kolose',
      '1tes' => '1tesalonika',
      '2tes' => '2tesalonika',
      '1tim' => '1timotius',
      '2tim' => '2timotius',
      'tit' => 'titus',
      'flm' => 'filemon',
      'ibr' => 'ibrani',
      'yak' => 'yakobus',
      '1ptr' => '1petrus',
      '2ptr' => '2petrus',
      '1yoh' => '1yohanes',
      '2yoh' => '2yohanes',
      '3yoh' => '3yohanes',
      'yud' => 'yudas',
      'why' => 'wahyu'
    );

  public function index() {
  }

  public function baca() {
    if ($this->request->is('ajax')) {
      $this->autoRender = false;
      $this->response->type('json');

      $regex4value = '/^\s*(\d+)\s*([-]?)\s*(\d+[:]?\d*)?$/';
      $regkitab = '/((?:\d?\s+)?(?:kej|kejadian|kel|keluaran|im|imamat|bil|bilangan|ul|ulangan|yos|yosua|hak|hakim-hakim|rut|sam|samuel|raj|raja-raja|taw|tawarikh|ezr|ezra|neh|nehemia|est|ester|ayb|ayub|mzm|mazmur|ams|amsal|pkh|pengkotbah|kid|kidung agung|yes|yesaya|yer|yeremia|rat|ratapan|yeh|yehezkiel|dan|daniel|hos|hosea|yl|yoel|am|amos|ob|obaja|yun|yunus|mi|mikha|nah|nahum|hab|habakuk|zef|zehanya|hag|hagai|za|zakharia|mal|maleakhi|mat|matius|mrk|markus|luk|lukas|yoh|yohanes|kis|kisah para rasul|rm|roma|kor|korintus|gal|galatia|ef|efesus|flp|filipi|kol|kolose|tes|tesalonika|tim|timotius|tit|titus|flm|filemon|ibr|ibrani|yak|yakobus|ptr|petrus|yud|yudas|why|wahyu))\s+(\d{1,3})/i';
      $condition = array();

      $string = $this->request->params;

      // misal: 1+Yoh+3:16-;25-30;40-42;1+Yoh+1:10-
      // $key = 1 Yoh 3
      // $value = 16-;25-30;40-42;1+Yoh+1:10-
      $key = key($string['named']);
      if (!$key) {
        echo json_encode(array('code'=>'900', 'message' => 'Format permintaan tidak valid!'));
        exit;
      }
      $value = $string['named'][$key];

      // tokenisasi 1 Yoh 3
      //
      if (!preg_match($regkitab, $key, $arrkitab)) {
        echo json_encode(array('code'=>'901', 'message' => 'Nama Kitab tidak ditemukan!'));
        exit;
      }
      $nmkitab = strtolower(preg_replace('/\s+/', '', $arrkitab[1]));
      if ((preg_match('/^[0-9]/', $nmkitab) && strlen($nmkitab) > 4) || (!preg_match('/^[0-9]/', $nmkitab) && strlen($nmkitab) > 3)) {
          $nmkitab = array_search($nmkitab, self::$kamusKitab);
      }
      $nopasal = $arrkitab[2];

      // tokenisasi 16-;25-30;40-42;1+Yoh+1:10-
      // 1. pisahkan daftar nilai berdasar ";"
      $hasil = explode(';', $value);

      // 2. ambil setiap nilai dari daftar yang dihasilkan
      $cnt = 0;
      foreach($hasil as $val){
        if (preg_match($regex4value, $val, $arr)) {
          // 2.a. cek apakah ayat mengikuti pola $regex4value
          $condition[$cnt]['kitabshort'] = $nmkitab;
          $condition[$cnt]['pasal'] = $nopasal; //$arrkitab[2];
          if (isset($arr[2]) && $arr[2] === '-') {
            if (isset($arr[3])) {
              // cek apakah range kedua berisi pasal ?
              $range2 = explode(':', $arr[3]);
              if (sizeof($range2) < 2) {
                $condition[$cnt]['ayat BETWEEN ? AND ?'] = array($arr[1], $arr[3]);
              } else {
                $condition[$cnt]['ayat >= '] = $arr[1];
                $cnt++;
                $condition[$cnt]['kitabshort'] = $nmkitab;
                $condition[$cnt]['pasal'] = $range2[0];
                $nopasal = $range2[0];
                $condition[$cnt]['ayat <= '] = $range2[1];
              }
            } else {
              $condition[$cnt]['ayat >= '] = $arr[1];
            }
          } else {
            $condition[$cnt]['ayat'] = $arr[1];
          }
        } else if (preg_match('[:]', $val, $arr)) {
          // 2.b. cek apakah ayat mengikuti pola $regkitab
          // 2.b.1. pisahkan kitab dan ayat berdasar ":"
          $hasilKomponen = explode(':', $val);
          if (sizeof($hasilKomponen) == 2) {
            // 2.b.2. cek format sebelum :
            if (preg_match('/^\s*(\d+)$/', $hasilKomponen[0], $arrkitab)) {
              // 2.b.2.1 jika mengandung pasal
              $condition[$cnt]['kitabshort'] = $nmkitab;
              $condition[$cnt]['pasal'] = $arrkitab[1];
              $nopasal = $arrkitab[1];
            } else if (preg_match($regkitab, $hasilKomponen[0], $arrkitab)) {
              // 2.b.2.2 jika mengandung kitab dan pasal
              $nmkitab = strtolower(preg_replace('/\s+/', '', $arrkitab[1]));
      	      if ((preg_match('/^[1-9]/', $nmkitab) && strlen($nmkitab) > 4) || (!preg_match('/^[1-9]/', $nmkitab) && strlen($nmkitab) > 3)) {
             		   $nmkitab = array_search($nmkitab, self::$kamusKitab);
      	      }
              $condition[$cnt]['kitabshort'] = $nmkitab;
              $condition[$cnt]['pasal'] = $arrkitab[2];
              $nopasal = $arrkitab[2];
            } else {
              // 2.b.2.3 jika tidak mengandung kitab, pasal
              echo json_encode(array('code'=>'901', 'message' => 'Nama Kitab tidak ditemukan!'));
              exit;
            }

            // 2.b.3. tokenisasi mengikuti pola $arrayat untuk arr[1]
            preg_match($regex4value, $hasilKomponen[1], $arr);
            if (isset($arr[2]) && $arr[2] === '-') {
              if (isset($arr[3])) {
                $condition[$cnt]['ayat BETWEEN ? AND ?'] = array($arr[1], $arr[3]);
              } else {
                $condition[$cnt]['ayat >= '] = $arr[1];
              }
            } else {
              $condition[$cnt]['ayat'] = $arr[1];
            }
          } else {
            $condition = array();
            break;
          }
        }
        $cnt++;
        if ($cnt > 3) { break; }
      }

      if (sizeof($condition) > 0) {
        $dataset = $this->Alkitab->find('all',
                      array(
                        'fields' => array('kitabshort', 'kitab', 'pasal', 'ayat', 'firman'),
                        'conditions'=> array('or' => $condition))
                      );
        echo json_encode($dataset);
      } else {
        echo json_encode(array('code'=>'902', 'message' => 'Ayat Alkitab tidak ditemukan!'));
      }
      $this->_stop();

    } else {
        $this->response->type('json');
        $this->response->statusCode(400);
        $this->response->body(json_encode(
            array('status' => 'ERROR', 'message' => 'Method is not allowed')));
        $this->response->send();
        $this->_stop();
    }
  }
}
