サーバーID	xs03110311
PW　a19941215



tk.loves.rednice@gmail.com
19941215
phrj824352

サイト      xs206760.xsrv.jp

サーバー番号	sv14398
ホスト名		sv14398.xserver.jp
IP アドレス	162.43.121.199

パスフレーズ    19941215

https://phpmyadmin-sv14398.xserver.jp/?
MySQL データベース		xs206760_amazon
MySQL ユーザ		xs206760_shibuya
パスワード			19941215

#既存の public_html ディレクトリを退避
mv /home/username/username.xsrv.jp/public_html /home/username/username.xsrv.jp/\_public_html
mv /home/xs206760/xs206760.xsrv.jp/public_html /home/xs206760/xs206760.xsrv.jp/\_public_html

#シンボリックリンクを作成する
ln -s /home/username/projectname/public /home/username/username.xsrv.jp/public_html
ln -s /home/xs206760/amazon_line/public /home/xs206760/xs206760.xsrv.jp/public_html
