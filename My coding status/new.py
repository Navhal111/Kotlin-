import json
import base64
import jwt
from os import environ as env, path
import urllib
from functools import wraps
from flask_cors import cross_origin
from jose import jwt,jwk
from jose.utils import base64url_encode
from jose.utils import base64url_decode
from flask import Flask, redirect, url_for, request ,session ,escape ,jsonify ,_app_ctx_stack
from auth0.v3.authentication import GetToken
from auth0.v3.authentication import Users
from flaskext.mysql import MySQL

domain = 'abdul4343.eu.auth0.com'
client_id = 'xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1'

hmac_key = {"alg":"RS256","kty":"RSA","use":"sig","n":"7gCkfItrz-tyX9D5kHPOAZREaAdAiVnOboNuGXYS-B0croWoN3cTC42tM_rR6CX6iXJWwxGNb5qqBM8_ppQ-ZGGgpVd13c99NvIKB6VdsYlkIL83SSwe7B_z7JJHVU-8n-tyEa_yjItiXLfaA--B0rv3aeqEpnFPsTiYt72golhIxBoHsVEBbw8JiQQWaj_0e2nhGoc6Aa2MGSzWLk9POOghpq03915Mtt9LPpuHei1CU10_--wfyct70rI9FU0CDxkYNo9BFeSsoQPEQrxD4hY3wC8uXPuLYjebgxz7Jz-8Sk2QjJJTo-vKPi1ediIJ2C1YgTNViT1wEZ6OMb_UWQ","e":"AQAB","kid":"Qzc2MzE5NjQxNjhFMzhCRkM1QkEwMkEyMTBFODNGQ0E1RjA5N0U5MA"}






def handle_error(error, status_code):
    resp = jsonify(error)
    resp.status_code = status_code
    return resp

mysql = MySQL()
app = Flask(__name__)
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = ''
app.config['MYSQL_DATABASE_DB'] = 'user'
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
mysql.init_app(app)

'''@app.route('/callback')
def callback_handling():
    code = request.args.get('code')
    get_token = GetToken('riteshlight.auth0.com')
    auth0_users = Users('riteshlight.auth0.com')
    token = get_token.authorization_code('ilCOEZU0TpNsVniL3EUm8AC6jQw521Ic',
                                         'izQcfbRKM3eVsfGfSXxGHRc-HOPcoyjL4Z_YHK756TuT4-0L6atrR3chMxxf62kJ', code, 'http://192.168.0.11:5000/callback')
    user_info = auth0_users.userinfo(token['access_token'])
    session['profile'] = json.loads(user_info)
    return redirect('/dashboard')

def requires_auth(f):
  @wraps(f)
  def decorated(*args, **kwargs):
    if 'profile' not in session:
      # Redirect to Login page here
      return redirect('/')
    return f(*args, **kwargs)

  return decorated

@app.route("/dashboard")
@requires_auth
def dashboard():
    return "wallcome"
'''

app = Flask(__name__)
def requires_auth(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        auth = request.headers.get('Authorization', None)
        print (auth)
        if not auth:
            return handle_error({'code': 'authorization_header_missing', 'description': 'Authorization header is expected'}, 401)
        parts = auth.split()
        if parts[0].lower() != 'bearer':
            return handle_error({'code': 'invalid_header', 'description': 'Authorization header must start with' 'Bearer'}, 401)
        elif len(parts) == 1:
            return handle_error({'code': 'invalid_header', 'description': 'Token not found'}, 401)
        elif len(parts) > 2:
            return handle_error({'code': 'invalid_header','description': 'Authorization header must be' 'Bearer + \s + token'}, 401)
        token = str(parts[1])
        jsonurl = urllib.urlopen("https://abdul4343.eu.auth0.com/.well-known/jwks.json")
        jwks = json.loads(jsonurl.read())
        for k in jwks["keys"]:
            hmack_key=k
        key = jwk.construct(hmack_key)
        print(hmack_key)
        unverified_header = jwt.get_unverified_header(token)
        message, encoded_sig = token.rsplit('.', 1)
        decoded_sig = base64url_decode(encoded_sig)
        try:
            payload = jwt.decode(
                         token,
                         hmack_key,
                         audience='590c096f64799e12734a1d2c',
                         algorithms=unverified_header["alg"]
                          )
            _app_ctx_stack.top.current_user = payload
        except jwt.ExpiredSignatureError:
               return handle_error({'code': 401, 'description': 'token is expired'}, 401)
        except jwt.JWTClaimsError:
               return handle_error({'code': 401, 'description': 'incorrect audience'}, 401)
        except Exception:
                return handle_error({'code': 401, 'description':'Unable to parse authentication token.'}, 400)
        return f(*args, **kwargs)
    return decorated

@app.route('/')
def hello():
    return 'Server is working'

@app.route('/call')
def callback_handling():
    code = request.args.get('code')
    print('code')
    get_token = GetToken('abdul4343.eu.auth0.com')
    auth0_users = Users('abdul4343.eu.auth0.com')
    token = get_token.authorization_code('xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1',
                                         'rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO', code, 'https://192.168.0.11:5000/callback')
    user_info = auth0_users.userinfo(token['access_token'])
    session['profile'] = json.loads(user_info)
    print session['profile']
    return redirect('/dashboard')

@app.route("/dashboard")
@requires_auth
def dashboard():
    return session['profile']

@app.route('/hello',methods=["GET"])
@cross_origin(headers=['Content-Type', 'Authorization'])
@requires_auth
def hello_world():
    return 'Hello, World!'

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
@requires_auth
def add():
    conn = mysql.connect()
    username = request.json['username']
    password = request.json['password']
    #username = request.form['username']
    #password = request.form['password']
    cur = conn.cursor()
    #cur = mysql.connect().cursor()
    try:
        cur.execute("SELECT * from User where Username='" + username + "'")
        data = cur.fetchone()
        if data is None:
            cur.execute("INSERT INTO User(userName,password) VALUES ('" + username + "','"+ password +"')")
            conn.commit()
	        #return "data has been saved"
	        #return sus
            return jsonify( success = True,massage = 'data has been saved')
	        #return json.dumps("Data has been saved")
	        #cur.execute("SELECT * from User")
	        #return jsonify(data=cur.fetchall())
        else:
            return jsonify( success = False,massage = 'User name allredy exist')
    except:
         conn.rollback()
         #return jsonify(data=cur.fetchall())
         return jsonify( success = False,massage ='data has not been saved')



@app.route("/Authe",methods = ['POST', 'GET'])
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
    return "logout"

if __name__ == '__main__':
   app.secret_key = 'A0Zr98j/3yX R~XHH!jmN]LWX/,?RT'
   app.run(host="0.0.0.0")
