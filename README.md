# Wifi 万能钥匙密码查询API

为了防止滥用，我把salt、aes_key、aes_iv 全都隐藏了，还有下面的一些请求参数; 请大家 Fill in the blank!

# Mac 用法

把整个 wifi 目录都放到服务器，本机用脚本编码一下 URL，然后用 qrencode 生成二维码后用微信扫描访问

![截图] (https://raw.githubusercontent.com/CaledoniaProject/Wifi-Master-Key-Protocol-Decrypted/master/contrib/example.png "Desktop screenshot")

暂时不知道 iOS 怎么获取 AP 列表 :-(

貌似不越狱不能用 Apple80211 库，只有在 /Application 下面的 app 才能用私有库吧
