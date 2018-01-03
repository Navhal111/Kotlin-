import http.client
import requests
from requests.auth import HTTPBasicAuth
import json
from auth0.v3.authentication import GetToken
from auth0.v3.authentication import *


conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")

payload = "{\"client_id\":\"xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1\",\"client_secret\":\"rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO\",\"audience\":\"urn:auth0-authz-api\",\"grant_type\":\"client_credentials\"}"

headers = { 'content-type': "application/json" }

conn.request("POST", "/oauth/token", payload, headers)
res = conn.getresponse()
data = res.read()
manage = json.loads(data.decode("utf-8"))
print(manage['access_token'])

conn = http.client.HTTPSConnection("abdul4343.eu.webtask.io")

headers = {
    "authorization": "Bearer " + manage['access_token'],
    "Cache-Control": "no-cache"
    }

#get user role
conn.request("GET" ,"/adf6e2f2b84784b57522e3b19dfc9201/api/users/auth0|59117862f91f750af1b77e65/roles",payload,headers)
res = conn.getresponse()
data = res.read()
u_role=json.loads(data.decode("utf-8"))
print(u_role[0]['name'])
payload = "[\"08787a0a-6cf1-4cec-be76-baebd620b535\"]"
#
#set user role
conn.request("PATCH" ,"/adf6e2f2b84784b57522e3b19dfc9201/api/users/auth0|590c0eb8554cf3241890567e/roles",payload,headers)
res = conn.getresponse()
data = res.read()
