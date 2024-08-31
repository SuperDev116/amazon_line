<?php
本番環境
https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem
テスト環境
https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem

https://developer.yahoo.co.jp/webapi/shopping/editItem.html

seller_id = ストアアカウントを指定します。
item_code = 商品コード（半角英数字、ハイフンのみ。99文字以内）※検索対象
path
name
price

item_image_urls
caption 商品説明
explanation 商品情報
product_code
jan

Client ID:
dj00aiZpPVJpRFdoRUdpY3pUaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWE-
シークレット:
jOr0rr7kmvXR9r5T6ERkXfN7KpGTu4vmd8TC8Ahv



basicAuth = base64_encode（$applicationId . ':' . $secret）;
base64_encode（'dj00aiZpPVJpRFdoRUdpY3pUaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWE:jOr0rr7kmvXR9r5T6ERkXfN7KpGTu4vmd8TC8Ahv')

URL： https://auth.login.yahoo.co.jp/yconnect/v1/token
サポートするHTTPメソッド： POST
Content-Type： application/x-www-form-urlencoded
Authorization： Basic 認証(basicAuth)

grant_type	○	authorization_code という固定文字列を指定してください。
code	○	認可コードを指定してください。一度リクエストした認可コードは使用できなくなります。再度リクエストする際にはAuthorizationエンドポイントで再取得してください。
redirect_uri	○	Authorizationエンドポイントで指定したUR

Host: auth.login.yahoo.co.jp
Authorization: Basic ZGoweUptazljbTgxVTIxNWRWTnNjbmRQSm1ROVdWZHJPVTB5TVd4a1NGcEpUakpOYldOSGJ6bE5RUzB0Sm5NOVkyOXVjM1Z0WlhKelpXTnlaWFFtZUQwMU5RLS06N0ZqZnAwWkJyMUt0RFJibmZWZG1Jdw==
Content-Type: application/x-www-form-urlencoded;charset=UTF-8

grant_type=authorization_code&code=kr4ch7ga&redirect_uri=http%3A%2F%2Fsample.domain.yahoo.co.jp%2Fcallback_path

アクセストークン更新時のリクエストパラメーター
パラメーター	必須	説明
grant_type	○	refresh_token という固定文字列を指定してください。
refresh_token	○	発行されたリフレッシュトークン

https://developer.yahoo.co.jp/yconnect/v1/server_app/explicit/token.html
https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem
Authorization:

seller_id=test-stor&
item_code=960977-001&
name='swsapi-vrimg-test-item004'&
product_category=1485&
price=100&
subcodes='色:ネイビー#サイズ:S=subcodetest-1|色:ネイビー#サイズ:M=subcodetest-2|色:グレー#サイズ:S=subcodetest-3|色:グレー#サイズ:M=subcodetest-4'&
subcode_images=
{
    "subcodetest-1":
    {
        "urls":["https://shopping.c.yimg.jp/lib/test-store/image.jpeg"],
        "main_flag":1
    },
    "subcodetest-2":
    {
        "urls":["https://shopping.c.yimg.jp/lib/test-store/image2.jpeg"],
        "main_flag":0
    }
}


POST /yconnect/v1/token HTTP/1.1
Host: auth.login.yahoo.co.jp
Authorization: Basic ZGoweUptazljbTgxVTIxNWRWTnNjbmRQSm1ROVdWZHJPVTB5TVd4a1NGcEpUakpOYldOSGJ6bE5RUzB0Sm5NOVkyOXVjM1Z0WlhKelpXTnlaWFFtZUQwMU5RLS06N0ZqZnAwWkJyMUt0RFJibmZWZG1Jdw==
Content-Type: application/x-www-form-urlencoded;charset=UTF-8

grant_type=authorization_code&code=kr4ch7ga&redirect_uri=http%3A%2F%2Fsample.domain.yahoo.co.jp%2Fcallback_path



?>