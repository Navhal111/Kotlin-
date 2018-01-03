import http.client
import requests
from requests.auth import HTTPBasicAuth
import json
from auth0.v3.authentication import GetToken
from auth0.v3.authentication import *


def add_form():
    try:
        form_data = request.form
        conn = mysql.connect()
        cur = conn.cursor()
        cur.execute("INSERT INTO user(email,password,address,hobby,gender) VALUES ('" +form_data['email'] + "','"+ form_data['password'] +"','"+form_data['address']+"','"+form_data['hobby']+"','"+form_data['gender']+"')")
        conn.commit()
        return jsonify( success = True,massage = 'data has been saved')

    except:
        conn.rollback()
        return jsonify( success = False,massage ='data has not been saved')

def find_role(user_id):
    conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")

    payload = "{\"client_id\":\"xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1\",\"client_secret\":\"rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO\",\"audience\":\"urn:auth0-authz-api\",\"grant_type\":\"client_credentials\"}"

    headers = { 'content-type': "application/json" }

    conn.request("POST", "/oauth/token", payload, headers)
    res = conn.getresponse()
    data = res.read()
    manage = json.loads(data.decode("utf-8"))
    conn = http.client.HTTPSConnection("abdul4343.eu.webtask.io")
    headers = {
        "authorization": "Bearer " + manage['access_token'],
        "Cache-Control": "no-cache"
        }
    conn.request("GET" ,"/adf6e2f2b84784b57522e3b19dfc9201/api/users/"+user_id+"/roles",payload,headers)
    res = conn.getresponse()
    data = res.read()
    u_role = json.loads(data.decode("utf-8"))
    return u_role[0]['name']


# conn1 = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
#
# payload = "{\"grant_type\":\"client_credentials\",\"client_id\": \"xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1\",\"client_secret\": \"rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO\",\"audience\": \"https://abdul4343.eu.auth0.com/api/v2/\"}"
# #payload = "{\"grant_type\":\"client_credentials\",\"client_id\": \"xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1\",\"client_secret\": \" \",\"audience\": \"https://abdul4343.eu.auth0.com/api/v2/\"}"
# headers = { 'content-type': "application/json" }
#
# conn1.request("POST", "/oauth/token", payload, headers)
#
# res = conn1.getresponse()
# data = res.read()
# m=json.loads(data.decode("utf-8"))

man =  open('man.txt','r')
manage = man.read().decode("utf-8").splitlines()
#hobby = "suffer"
#payload = "{\"user_metadata\":{\"hobby\":\""+hobby+"\"}}"
#payload ="{\"read\":\"users\"}"
payload = ""

#payload = "{\"name\":\"My Sample API\",\"identifier\": \"https://my-api-urn\",\"signing_alg\": \"RS256\",\"scopes\": [{ \"value\": \"sample-scope\", \"description\": \"Description for Sample Scope\"}]}"
#print(payload)
# headers = {
#     "authorization": "Bearer " + m['access_token'],
#     "Cache-Control": "no-cache"
#     }

conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
headers = {
    "authorization": "Bearer " + manage[0],
    "Cache-Control": "no-cache"
    }

conn.request("GET" ,"/api/v2/users",payload,headers)
#conn.request("DELETE" ,"/api/v2/users/auth0|59116c63bdde8f2df76d33ba",payload,headers)
#response = requests.delete("http://abdul4343.eu.auth0.com/api/v2/users/auth0|590c0f29d6668570f3202691",headers)
# print(response)
res = conn.getresponse()
data = res.read()
userdata = json.loads(data.decode("utf-8"))
# if userdata['user_metadata']:
#     print(userdata['logins_count'])
i=0
user_data=[]
b={}
for us in userdata:
    b["email"] = us['email']
    b["created_at"] = us['created_at']
    b["name"] = us['name']
    b["user_id"] = us['user_id']
    b["last_login"]=us['last_login']
    user_data.append(dict(b))
    print(user_data)
