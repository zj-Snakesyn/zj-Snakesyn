<?php
// 目标m3u文件的URL
$url = 'https://live.fanmingming.com/tv/m3u/ipv6.m3u';

// 使用cURL获取m3u文件内容
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$m3uContent = curl_exec($ch);
curl_close($ch);

if ($m3uContent === false) {
    echo "无法获取m3u文件内容。";
    exit;
}

// 解析m3u内容为数组
$m3uLines = explode("\n", $m3uContent);

// 创建一个数组来存储频道数据
$channels = [];
$currentChannel = null;

// 遍历m3u内容行
foreach ($m3uLines as $line) {
    $line = trim($line);

    if (empty($line)) {
        continue;
    }

    if (strpos($line, '#EXTINF:') === 0) {
        // 新频道开始
        $currentChannel = [
            'name' => substr($line, strpos($line, ',') + 1),
            'url' => '',
        ];
    } elseif ($currentChannel !== null) {
        // 将URL添加到当前频道
        $currentChannel['url'] = $line;
        $channels[] = $currentChannel;
        $currentChannel = null;
    }
}

// 整理频道数据并写入1.txt
$channelText = '';
foreach ($channels as $channel) {
    $channelText .= $channel['name'] . ",".$channel['url']. "\n";
}

file_put_contents('1.txt', "$"."c_start IPV6直播"."$"."c_end\n" . $channelText);
echo "数据已写入1.txt文件。";


?>
