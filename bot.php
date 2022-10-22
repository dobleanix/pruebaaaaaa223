<?php
    date_default_timezone_set("Asia/kolkata");
    //Data From Webhook
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chat_id = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];
    $message_id = $update["message"]["message_id"];
    $id = $update["message"]["from"]["id"];
    $username = $update["message"]["from"]["username"];
    $firstname = $update["message"]["from"]["first_name"];
    $start_msg = $_ENV['START_MSG']; 

if($message == "/start"){
    send_message($chat_id,$message_id, "Porfavor proporcione 6 números para verificar\n__`/bin xxxxxx`__");
}

//Bin Lookup
if(strpos($message, "!bin") === 0){
    $bin = substr($message, 5);
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://bin-check-dr4g.herokuapp.com/api/".$bin,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
    "accept-language: en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7",
    "sec-fetch-dest: document",
    "sec-fetch-site: none",
    "user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1"
   ],
   ]);

 $result = curl_exec($curl);
 curl_close($curl);
 $data = json_decode($result, true);
 $bank = $data['data']['bank'];
 $bin = $data['data']['bin']
 $country = $data['data']['countryInfo']['name'];
 $brand = $data['data']['vendor'];
 $level = $data['data']['level'];
 $type = $data['data']['type'];
 $emoji = $data['data']['countryInfo']['emoji'];
 $currency $data['data']['countryInfo']['code']
 $result1 = $data['result'];

    if ($result1 == true) {
    send_message($chat_id,$message_id, """
    <b>✅Bin Valid</b>
    <b>⚜Bin: </b> <code>$bin</code>
    <b>💳Info: </b> <code>$brand -</code><code> $type -</code><code> $level</code>
    <b>🏛Bank: </b> <code>{bank}</code>
    <b>🌎Country: </b> <code>$country </code><code>$emoji</code>
    <b>💲Currency: </b> <code>$currency</code>
    <b>🙍Checked By: @$username</b>
    <b>🤖Bot by: @camilafuentes</b>
    """);
    }
else {
    send_message($chat_id,$message_id, "**⚜Bin -** `$bin`\n**❌Status -** `Invalid Bin`\n\n**🙍Checked By: ** @$username\n**Bot by: @camilafuentes**");
}
}
    function send_message($chat_id,$message_id, $message){
        $text = urlencode($message);
        $apiToken = $_ENV['5496366652:AAEZ35ZeVdAqPJDte5h4aZih4Gyk-HnM28w'];  
        file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=$text&parse_mode=HTML");
    }
?>