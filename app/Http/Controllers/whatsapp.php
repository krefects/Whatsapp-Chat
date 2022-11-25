<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class whatsapp extends Controller
{

 public function webhook(Request $request){
  $data = file_get_contents("php://input");
  $event = json_decode($data);
  try {
    $this->database = \App\Services\FirebaseService::connect();
    $is_mensagem = false;
    if(isset($event->entry[0]->changes[0]->value->contacts[0]->wa_id)){
      $chat_number = $event->entry[0]->changes[0]->value->contacts[0]->wa_id;
      $is_mensagem = true;
      $mensagem_data = (int) $event->entry[0]->changes[0]->value->messages[0]->timestamp;
    }else{
      $chat_number = $event->entry[0]->changes[0]->value->statuses[0]->recipient_id;
      $mensagem_data = (int) $event->entry[0]->changes[0]->value->statuses[0]->timestamp;
    }
    //// REMOVER O NONO DIGITO;
    $ddd = substr($chat_number,0,4);
    $chat_number = substr($chat_number,-8);
    $chat_number = $ddd.$chat_number;
 
    if($is_mensagem == true){
      $data = [
        'telefone' => $chat_number,'ultima_mensagem' => date('Y-m-d H:i:s'),
        'visto' => false,
        'nome' => $event->entry[0]->changes[0]->value->contacts[0]->profile->name
      ];
      $set = $this->database
      ->getReference('whatsapp_chat/chats_numeros/'.$chat_number)
      ->update($data);
      $this->database
      ->getReference('whatsapp_chat/novas_mensagens')
      ->push([
        'numero' => $chat_number,
        'created_at_time' => $mensagem_data,
        'data' => $event
      ]);
    }
    
    $this->database
    ->getReference('whatsapp_chat/mensagens/'.$chat_number)
    ->push([
      'created_at' => date('Y-m-d H:i:s'),
      'created_at_time' => $mensagem_data,
      'data' => $event
    ]);

    // fim salvar no chat modelo novo

  } catch (\Exception $e) {
    var_dump($e);

  }
}


}