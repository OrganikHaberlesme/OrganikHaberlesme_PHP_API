<?php
$apiKullaniciAdi="username";
$apiKullaniciSifresi="password";

$apiKEY=md5($apiKullaniciAdi.$apiKullaniciSifresi);
$gsms=array("5445359675","5444444444","5355555555","5366666666","5411111111");
$jData=array();
$jData["data"]=array();
$jData["data"]["global_options"]=array(
    "header"=>"TEST",
    "message_format"=>0,
    "timeout"=>48,
    "gsm_isUnique"=>0
);
$jData["data"]["deliveries"]=array();

for ($i=0;$i<count($gsms);$i++){
  $delivery=array();

	// test - mesaj metni.
  $message="deneme mesaj #GSM#, test: ".$i;
	
	// mesaj metni BASE64 UTF8 encode olma zorunluluğu var.
  $delivery["message"]=base64_encode($message);

  $delivery["recipients"]=array();

	// gruptan sms gönderimi yapacaksanız. gsms yollamanıza gerek yok. grup adlarını yazabilirsiniz.
	// $delivery["recipients"]["groups"]=array();
	
  $delivery["recipients"]["gsms"]=array();

	// aynı mesajın gitmesini istediğiniz numara bu satirda birden fazla tanımlama yapabilirsiniz.
	// mesajların tamamı aynı olacaksa. döngüyü burdan başlatabilir veya direk dizin olarak tanımlama yabilirsiniz.
	// $delivery["recipients"]["gsms"]=array("5445359675","5444444444","5355555555","5366666666","5411111111");
	
  $delivery["recipients"]["gsms"][]=$gsms[$i];

	
	
  array_push($jData["data"]["deliveries"],$delivery);
}//for

// haberleşme JSON formatında olmalı. veya xml olarak da gönderebilirsiniz. JSON en iyisi :)
$jData=json_encode($jData);

$url = 'https://organikapi.com/v2/'.$apiKEY.'/sendsms/';
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url);
curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch2, CURLOPT_POSTFIELDS, $jData);

curl_setopt($ch2 , CURLOPT_FRESH_CONNECT ,  true); // her zaman yeni bir bağlantı açmalı. eskiyi kullanmasın.
curl_setopt($ch2 , CURLOPT_TIMEOUT ,  180); // sunucudan gelecek cevap önemli değilse, bu süreyi "2" yapabilirsiniz.
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0); // SSL host hatasını önemseme.
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0); // Ssl doğrulama hatasını önemseme.
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true); // curl_exec komutu ile sonucu değişkene atar. direk ekrana basmasını engeller.


$sonucCurlTXT=curl_exec($ch2);
$sonucObject=json_decode($sonucCurlTXT);

$curl_error=curl_error($ch2);

if (!empty($curl_error)){
  echo "curl_error : ".$curl_error;
	
}else if (!empty($sonucObject->response->error)){
  echo "SMS - bir hata oluştu : ".$sonucObject->response->error->message;

}else if(!empty($sonucObject->response->result)){
  echo "islem tamam";

}else{
  echo "bir hata olustu. lütfen sunucunuzun ayarlarını kontrol ediniz.\n * cURL ve OpenSSL aktif olması gerekmektedir.\n * curl_exec, file_get_content, curl_init fonksiyonlarının disabled olmaması gerekmektedir.";
}

curl_close($ch2);
