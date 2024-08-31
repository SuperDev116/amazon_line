import mysql.connector
import ast
import base64
import requests

applicationId = "dj00aiZpPVJpRFdoRUdpY3pUaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWE-"
secret = "jOr0rr7kmvXR9r5T6ERkXfN7KpGTu4vmd8TC8Ahv"
auth_token = base64.b64encode((applicationId + ':' + secret).encode('ascii'))

def amazonToYahooUpload():
    try:
        connection = mysql.connector.connect(host='localhost',
                                                database='comparesite',
                                                user='root',
                                                password='')

        sql_select_Query = "select * from amazon_add_list where state='uploading'"
        cursor = connection.cursor()
        cursor.execute(sql_select_Query)
        records = cursor.fetchall()

        #print("Total number of rows in table: ", cursor.rowcount)

        for row in records:
            
            try:
                sql_select_Query_amazon_list = "select * from amazon_add_list where state='uploading'"

                from amazon_paapi import AmazonApi

                AMAZON_API_KEY = "AKIAJQRJMEC4WIFOESSQ"
                AMAZON_API_SECRET_KEY = "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"
                AMAZON_ASSOCIATE_TAG = "gnem03010a-22"
                AMAZON_HOST = "webservices.amazon.co.jp"
                AMAZON_REGION = "us-west-2"
                AMAZON_COUNTRY = "JP"
                
                amazon_asin_list = ast.literal_eval(row[2])

                amazon = AmazonApi(AMAZON_API_KEY, AMAZON_API_SECRET_KEY, AMAZON_ASSOCIATE_TAG, AMAZON_COUNTRY)
                
                try:
                   
                    for asin in amazon_asin_list:

                        item = amazon.get_items(asin)[0]

                        print(asin, ">>>", item)

                        auth_token = "ZGowMGFpWnBQVkpwUkZkb1JVZHBZM3BVYVNaelBXTnZibk4xYldWeWMyVmpjbVYwSm5nOU1XRS06ak9yMHJyN2ttdlhSOXI1VDZFUmtYZk43S3BHVHU0dm1kOFRDOEFodg=="
                        hed = {'Authorization': 'Bearer ' + auth_token}
                        data = {
                            'seller_id' : 'test-store',
                            'item_code' : '960977-001',
                            'name' : 'swsapi-vrimg-test-item004',
                            'product_category' : '1485',
                            'price' : '10000',
                            'subcodes' : '色:ネイビー#サイズ:S=subcodetest-1|色:ネイビー#サイズ:M=subcodetest-2|色:グレー#サイズ:S=subcodetest-3|色:グレー#サイズ:M=subcodetest-4',
                            'subcode_images':
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
                        }

                        url = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem'
                        # response = requests.post(url, json=data, headers=hed)
                        
                except:
                    print("Error reading data from MySQL table")
               
            except mysql.connector.Error as e:
                print("Error reading data from MySQL table", e)

    except mysql.connector.Error as e:
        print("Error reading data from MySQL table", e)

amazonToYahooUpload()

