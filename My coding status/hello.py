import json
import jwt
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

AUTH0_DOMAIN = 'abdul4343.eu.auth0.com'
AUTH0_CLIENT_ID = 'xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1'
#client_secret = 'examplesecret'

def handle_error(error, status_code):
    resp = jsonify(error)
    resp.status_code = status_code
    return resp

def get_token_auth_header():
    auth = request.headers.get("Authorization", None)
    #print(auth)
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


def requires_auth(f):
    """Determines if the access token is valid"""
    @wraps(f)
    def decorated(*args, **kwargs):
        token = get_token_auth_header()
        print(token)
        jsonurl = urllib.urlopen("https://abdul4343.eu.auth0.com/.well-known/jwks.json")
        jwks = json.loads(jsonurl.read())
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
        print(rsa_key)
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
                print("header1")
                return handle_error({"code": "token_expired",
                                     "description": "token is expired"}, 401)
            except jwt.JWTClaimsError:
                print("header2")
                return handle_error({"code": "invalid_claims",
                                     "description": "incorrect claims,"
                                                    "please check the audience and issuer"}, 401)
            except Exception:
                return handle_error({"code": "invalid_header",
                                     "description": "Unable to parse authentication"
                                                    "token."}, 400)

            _app_ctx_stack.top.current_user = payload
            print(_app_ctx_stack.top.current_user)
            payload = ""
            return f(*args, **kwargs)
        return handle_error({"code": "invalid_header",
                             "description": "Unable to find appropriate key",'token':token}, 400)
    return decorated

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


# @app.route('/callback')
# def callback_handling():
#     code = request.args.get('code')
#     print(code)
#     get_token = GetToken('abdul4343.eu.auth0.com')
#     auth0_users = Users('abdul4343.eu.auth0.com')
#     token2 = get_token.authorization_code('xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1',
#                                          'rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO', code, 'https://192.168.0.11:5000/callback')
#     user_info = auth0_users.userinfo(token2['access_token'])
#     session['profile'] = json.loads(user_info)
#     return redirect('/dashboard')


@app.route("/dashboard")
@requires_auth
def dashboard():
    return login



@app.route('/data',methods = ['POST', 'GET'])
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def data():
    username = request.json['username']
    print(username)
    return 'Server is working'

@app.route('/hello')
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def hello_world():
    #return 'Hello, World!'
    #print(name)
    print("asfery")
    return jsonify(success = True,massage ='login')

@app.route('/name/<name>')
def name(name):
   return 'Name:- %s!' % name

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
    conn = http.client.HTTPSConnection("abdul4343.eu.auth0.com")
    #conn = mysql.connect()
    username = request.json['username']
    name = request.json['name']
    adress = request.json['address']
    number =request.json['number']
    cu_user=_app_ctx_stack.top.current_user

    payload = "{\"user_metadata\":{\"name\":\""+name+"\",\"address\":\""+adress+"\",\"ph_number\":\""+number+"\"}}"
    print(payload)
    headers = {
        'authorization': "Bearer " + token,
        'content-type': "application/json"
        }

    conn.request("PATCH", "/api/v2/users/"+cu_user['sub'], payload,headers)
    res = conn.getresponse()
    data = res.read()
    #print(data.decode("utf-8"))
    #password = request.json['password']
    #username = request.form['username']
    #password = request.form['password']
    return name
    # cur = conn.cursor()
    # #cur = mysql.connect().cursor()
    # try:
    #     cur.execute("SELECT * from user1 where email='" + username + "'")
    #     data = cur.fetchone()
    #     if data is None:
    #         cur.execute("INSERT INTO user1(email,name,address,number) VALUES ('" +username+ "','"+ name +"','"+adress+"','"+number+"'")
    #         conn.commit()
	#         #return "data has been saved"
	#         #return sus
    #         return jsonify( success = True,massage = 'data has been saved')
	#         #return json.dumps("Data has been saved")
	#         #cur.execute("SELECT * from User")
	#         #return jsonify(data=cur.fetchall())
    #     else:
    #         return jsonify( success = False,massage = 'User name allredy exist')
    # except:
    #      conn.rollback()
    #      #return jsonify(data=cur.fetchall())
    #      return jsonify( success = False,massage ='data has not been saved')
    # finally:
    #      conn.close()
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
       return "Logged in successfully"
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


@app.route("/addform",methods = ['POST','GET'])
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


@app.route("/logout")
def logout():
    session.pop('username', None)
    session.clear()
    return "logout"

if __name__ == '__main__':
   app.secret_key = 'A0Zr98j/3yX R~XHH!jmN]LWX/,?RT'
   app.run(host="0.0.0.0")
