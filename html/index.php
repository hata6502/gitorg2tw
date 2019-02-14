<?php
    require '/var/gitorg2tw/config.php';

    $header = getallheaders();
    $json = file_get_contents("php://input");
    $hmac = hash_hmac('sha1', $json, SECRET);
    if (isset($header['X-Hub-Signature']) && $header['X-Hub-Signature']==='sha1='.$hmac) {
        $payload = json_decode($json, true);
        $twitterText = '';
        $twitterText .= $payload['repository']['full_name'].'リポジトリ '.preg_replace('/^refs\/heads\//', '', $payload['ref'])."ブランチを更新しました。\n\n";
        foreach ($payload['commits'] as $commit) {
            $twitterText .= $commit['message']."\n";
        }
        $twitterText .= "\n".$payload['compare'];
        error_log($twitterText);
    }
?>
