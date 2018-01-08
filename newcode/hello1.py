import json
import jwt
import http.client
import requests
from requests.auth import HTTPBasicAuth
from jose.utils import base64url_encode
from jose.utils import base64url_decode
from os import environ as env, path
import urllib
from functools import wraps
from flask_cors import cross_origin
from jose import jwt,jwk
from flask import Flask, redirect, url_for, request ,session ,escape ,jsonify ,_app_ctx_stack
from auth0.v3.authentication import GetToken
from auth0.v3.authentication import Users
from flaskext.mysql import MySQL
import http.client

#client_secret = 'examplesecret'

def managment_api():
    conn1 = http.client.HTTPSConnection("abdul4343.eu.auth0.com")

    payload = "{\"grant_type\":\"client_credentials\",\"client_id\": \"xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1\",\"client_secret\": \"rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO\",\"audience\": \"https://abdul4343.eu.auth0.com/api/v2/\"}"

    headers = { 'content-type': "application/json" }

    conn1.request("POST", "/oauth/token", payload, headers)

    res = conn1.getresponse()
    data = res.read()
    return json.loads(data.decode("utf-8"))


def handle_error(error, status_code):
    resp = jsonify(error)
    resp.status_code = status_code
    return resp

def get_token_auth_header():
    auth = request.headers.get("Authorization", None)
    if not auth:
        return handle_error({"code": "authorization_header_missing",
                             "description":
                                 "Authorization header is expected"}, 401)

    parts = auth.split()

    if parts[0].lower() != "bearer":
        return handle_error({"code": "invalid_header",
                             "description":
                                 "Authorization header must start with"
                                 "Bearer"}, 401)
    elif len(parts) == 1:
        return handle_error({"code": "invalid_header",
                             "description": "Token not found"}, 401)
    elif len(parts) > 2:
        return handle_error({"code": "invalid_header",
                             "description": "Authorization header must be"
                                            "Bearer token"}, 401)

    token = parts[1]
    return token

def require_auth(f):
    """Determines if the access token is valid"""
    @wraps(f)
    def decorated(*args, **kwargs):
        token = get_token_auth_header()
        #jsonurl = urllib.urlopen("https://abdul4343.eu.auth0.com/.well-known/jwks.json")
        datakey =  open('data.txt','r')
        jwks = json.loads(datakey .read())
        unverified_header = jwt.get_unverified_header(token)
        rsa_key = {}
        for key in jwks["keys"]:
            if key["kid"] == unverified_header["kid"]:
                 rsa_key = {
                     "kty": key["kty"],
                     "kid": key["kid"],
                     "use": key["use"],
                     "n": key["n"],
                     "e": key["e"]
                 }
        if rsa_key:
            try:
                payload = jwt.decode(
                    token,
                    rsa_key,
                    algorithms=unverified_header["alg"],
                    audience='https://abdul4343.eu.auth0.com/api/v2/',
                    issuer="https://abdul4343.eu.auth0.com/"
                )
            except jwt.ExpiredSignatureError:
                return handle_error({"code": "token_expired",
                                     "description": "token is expired"}, 401)
            except jwt.JWTClaimsError:
                return handle_error({"code": "invalid_claims",
                                     "description": "incorrect claims,"
                                                    "please check the audience and issuer"}, 401)
            except Exception:
                return handle_error({"code": "invalid_header",
                                     "description": "Unable to parse authentication"
                                                    "token."}, 400)

            _app_ctx_stack.top.current_user = payload
            payload = ""
            return f(*args, **kwargs)
        return handle_error({"code": "invalid_header",
                             "description": "Unable to find appropriate key",'token':token}, 400)
    return decorated

def requires_auth(f):
    """Determines if the access token is valid"""
    @wraps(f)
    def decorated(*args, **kwargs):
        token = get_token_auth_header()
        #jsonurl = urllib.urlopen("https://abdul4343.eu.auth0.com/.well-known/jwks.json")
        datakey =  open('data.txt','r')
        jwks = json.loads(datakey .read())
        unverified_header = jwt.get_unverified_header(token)
        rsa_key = {}
        for key in jwks["keys"]:
            if key["kid"] == unverified_header["kid"]:
                 rsa_key = {
                     "kty": key["kty"],
                     "kid": key["kid"],
                     "use": key["use"],
                     "n": key["n"],
                     "e": key["e"]
                 }
        if rsa_key:
            try:
                payload = jwt.decode(
                    token,
                    rsa_key,
                    algorithms=unverified_header["alg"],
                    audience='xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1',
                    issuer="https://abdul4343.eu.auth0.com/"
                )
            except jwt.ExpiredSignatureError:
                return handle_error({"code": "token_expired",
                                     "description": "token is expired"}, 401)
            except jwt.JWTClaimsError:
                return handle_error({"code": "invalid_claims",
                                     "description": "incorrect claims,"
                                                    "please check the audience and issuer"}, 401)
            except Exception:
                return handle_error({"code": "invalid_header",
                                     "description": "Unable to parse authentication"
                                                    "token."}, 400)

            _app_ctx_stack.top.current_user = payload
            payload = ""
            return f(*args, **kwargs)
        return handle_error({"code": "invalid_header",
                             "description": "Unable to find appropriate key",'token':token}, 400)
    return decorated

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

mysql = MySQL()
app = Flask(__name__)
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = ''
app.config['MYSQL_DATABASE_DB'] = 'user'
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
mysql.init_app(app)

app = Flask(__name__)

@app.route('/')
def hello():
    return 'Server is working'

@app.route('/hello')
@cross_origin(headers=['Content-Type', 'Authorization'])
@require_auth
def hello_world():
    userinfo=_app_ctx_stack.top.current_user['sub']
    role=find_role(userinfo)
    if(role == 'user'):
        return handle_error({"code": "invalid_acsecc",
                         "description": "NOt a admin access_denied"
                                         "token."}, 401)
    session['user_id']=userinfo
    if(role == 'admin'):
        payload = ""
        man_token = managment_api()
        man=man_token['access_token']
        conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
        headers = {
                "authorization": "Bearer " + man,
                "Cache-Control": "no-cache"
                }

        conn.request("GET" ,"/api/v2/users",payload,headers)
        res = conn.getresponse()
        data = res.read()
        users=json.loads(data.decode("utf-8"))
        return "hello"
    else:
        return handle_error({"code": "invalid_acsecc",
                         "description": "NOt a admin access_denied"
                                        "token."}, 401)

@app.route('/applogin')
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def login_app():
    userinfo=_app_ctx_stack.top.current_user['sub']
    man_token = managment_api()
    man=man_token['access_token']
    payload=""
    headers = {
        "authorization": "Bearer " + man,
        "Cache-Control": "no-cache"
        }
    conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
    conn.request("GET" ,"/api/v2/users/"+userinfo,payload,headers)
    res = conn.getresponse()
    data = res.read()
    usersdata = json.loads(data.decode("utf-8"))
    if(usersdata['logins_count'] <= 1):
        payload=""
        headers = {
                "authorization": "Bearer " + man,
                "Cache-Control": "no-cache"
                }
        payload = "[\"08787a0a-6cf1-4cec-be76-baebd620b535\"]"
        conn.request("PATCH" ,"/adf6e2f2b84784b57522e3b19dfc9201/api/users/"+userinfo+"/roles",payload,headers)
        res = conn.getresponse()

    if not usersdata['user_metadata'] :
        return jsonify(success = False,massage ='Data not added')

    return jsonify(success = True,massage ='Data added')

@app.route('/users')
@cross_origin(headers=['Content-Type', 'Authorization'])
@require_auth
def users_info():
    userinfo=_app_ctx_stack.top.current_user['sub']
    role=find_role(userinfo)
    if(role == 'user'):
        return handle_error({"code": "invalid_acsecc",
                         "description": "NOt a admin access_denied"
                                        "token."}, 401)

    payload = ""
    man_token = managment_api()
    man=man_token['access_token']
    conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
    headers = {
            "authorization": "Bearer " + man,
            "Cache-Control": "no-cache"
            }

    conn.request("GET" ,"/api/v2/users",payload,headers)
    res = conn.getresponse()
    data = res.read()
    users=json.loads(data.decode("utf-8"))
    #session['id']=userinfo
    user_data=[]
    b={}
    for us in users:
        if(us['email'] != 'ylight@gmail.com'):
            b["email"] = us['email']
            b["created_at"] = us['created_at']
            b["name"] = us['name']
            b["user_id"] = us['user_id']
            b["last_login"]=us['last_login']
            user_data.append(dict(b))
    return json.dumps(user_data)

@app.route('/removeuser')
@cross_origin(headers=['Content-Type', 'Authorization'])
@require_auth
def remove():
    userinfo=_app_ctx_stack.top.current_user['sub']
    remove_id=request.json['user_id']
    role=find_role(userinfo)
    if(role == 'user'):
        return handle_error({"code": "invalid_acsecc",
                             "description": "NOt a admin access_denied"
                                            "token."}, 401)
    #use_id=result = request.json['id']
    if(role == admin):
        mange_token = managment_api()
        conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
        payload = ""
        headers = {
            "authorization": "Bearer " + mange_token['access_token'],
            "Cache-Control": "no-cache"
            }
        conn.request("DELETE" ,"/api/v2/users/"+remove_id+"",payload,headers)
        res = conn.getresponse()
        data = res.read()
        return jsonify( success = True,massage = 'Remove User')
    else:
        return handle_error({"code": "invalid_acsecc",
                              "description": "NOt a admin access_denied"
                                              "token."}, 401)

@app.route('/mark/<int:scro>')
def mark(scro):
    return 'marks :- %d ' %scro

@app.route('/show',methods = ['POST', 'GET'])
def show():
    if request.method == 'POST':
       result = request.form['name']
       return redirect(url_for('name',name = result))


@app.route('/add',methods = ['POST', 'GET'])
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def add():
    #conn = mysql.connect()
    FirstName = request.json['FirstName']
    LastName = request.json['LastName']
    gender =request.json['gender']
    DOB =request.json['DOB']
    Relgion=request.json['Relgion']
    mouther_tongue =request.json['mouther_tongue']
    country =request.json['country']
    cu_user=_app_ctx_stack.top.current_user
    payload = "{\"user_metadata\":{\"FirstName\":\""+name+"\",\"LastName\":\""+lastname+"\",\"gender\":\""+gender+"\",\"DOB\":\""+DOB+"\",\"Relgion\":\""+Relgion+"\",\"mouther_tongue\":\""+mouther_tongue+"\",\"Country\":\""+countey+"\"}}"
    print(payload)
    headers = {
        'authorization': "Bearer " + token,
        'content-type': "application/json"
        }
    conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
    conn.request("PATCH", "/api/v2/users/"+cu_user['sub'], payload,headers)
    res = conn.getresponse()
    data = res.read()
    print(data)
    #print(data.decode("utf-8"))
    #password = request.json['password']
    #username = request.form['username']
    #password = request.form['password']
    return jsonify(success = True,massage ='Data added',name=name)
@app.route("/admin",methods = ['POST', 'GET'])
def admin():
    username = request.json['username']
    password = request.json['password']
    #username = request.form['name']
    #password = request.form['pass']
    cursor = mysql.connect().cursor()
    cursor.execute("SELECT * from User where Username='" + username + "' and password='" + password + "'")
    data = cursor.fetchone()
    if data is None:
       session['uername']=username
       return "Username or Password is wrong"
    else:
       return jsonify( success = True,massage ='data has been saved')
    cursor.close()

@app.route("/auth",methods = ['POST', 'GET'])
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def Authe():
    username = request.json['username']
    password = request.json['password']
    #username = request.form['name']
    #password = request.form['pass']
    cursor = mysql.connect().cursor()
    cursor.execute("SELECT * from User where Username='" + username + "' and password='" + password + "'")
    data = cursor.fetchone()
    if data is None:
       session['uername']=username
       return "Username or Password is wrong"
    else:
       return "Logged in successfully"


@app.route("/logout")
@cross_origin(headers=['Content-Type', 'Authorization'])
@require_auth
def logout():
    session.pop('id', None)
    session.clear()
    print("logout")
    return "logout"

if __name__ == '__main__':
   app.secret_key = 'A0Zr98j/3yX R~XHH!jmN]LWX/,?RT'
   app.run(host="0.0.0.0")
