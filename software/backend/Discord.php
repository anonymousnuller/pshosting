<?php

$discord_webhook = new DiscordWebHook();
class DiscordWebHook extends Controller
{

    public function callWebhook($message, $webhookurl = null)
    {
        if(is_null($webhookurl)){
            $webhookurl = env('https://ptb.discord.com/api/webhooks/980590642587648020/8ZrLZp5_Fdki2DiTism5E_q_h7SsQLc5Na7ptqyEPOCzsOKKIFMWFgnMxwijdxuSfdO8');
        }
        $timestamp = date("c", strtotime("now"));
        $json_data = json_encode([
            "content" => $message,
            "username" => env('ProSideHosting Status'),
            "avatar_url" => env('DISCORD_AVATAR_URL'),
            "tts" => false,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        $ch = curl_init( $webhookurl );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec( $ch );
        curl_close( $ch );
    }
}