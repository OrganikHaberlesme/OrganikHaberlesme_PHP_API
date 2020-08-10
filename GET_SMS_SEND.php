<?php

$mesaj = "deneme mesaj deneme"; // Göndermek istediğiniz sms içeriğini giriniz.
$apiKullanici_adi = "test"; // API Kullanıcı adınızı girmelisiniz.
$apiKullanici_sifre = "test"; // API Kullanıcı parolanızı girmelisiniz.

$url = 'https://organikapi.com/v2/'.md5($apiKullanici_adi.$apiKullanici_sifre).'/smsviaget/?header=TEST&gsms=5441111111,5351111111,5368887777&message='.base64_encode($mesaj);

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true); // Sonucu ekrana yazdırır
curl_setopt($ch2 , CURLOPT_FRESH_CONNECT ,  true); // Her zaman yeni bağlantı başlatır.
curl_setopt($ch2 , CURLOPT_TIMEOUT ,  180); // Zaman aşımı değeri. (Saniye)
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0); // SSL hatasını dikkate almaz.

$sonucCurlTXT = curl_exec($ch2); // İşlemi başlatır.

curl_close($ch2); // Bağlantıyı kapatır.

$sonucObject=json_decode($sonucCurlTXT); // Gelen sonucu object olarak dönüştür.

print_r($sonucObject); // Sonucu ekrana yazdır.

if(!empty($sonucObject->response->error)){
  echo "teknik bir hata oluştu:".$sonucObject->response->error->message;

}else if(!empty($sonucObject->response->result)){
    echo "islem tamam";
    
}else{
    echo "bir hata olustu.";
    
}
