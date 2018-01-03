from auth0.v3.authentication import GetToken
from auth0.v3.authentication import Users
from dotenv import load_dotenv
from flask import Flask
from flask import redirect
from flask import render_template
from flask import request
from flask import send_from_directory
from flask import session
from functools import wraps
from jose import jwt,jwk
from flask import Flask, redirect, url_for, request ,session ,escape ,jsonify ,_app_ctx_stack
app = Flask(__name__)

# Here we're using the /callback route.
@app.route('/callback')
def callback_handling():
    code = request.args.get('code')
    get_token = GetToken('abdul4343.eu.auth0.com')
    auth0_users = Users('abdul4343.eu.auth0.com')
    token = get_token.authorization_code('xYbqBYbjcd2rS6o9Xb0HrLwQWHXu8jH1',
                                         'rZHMb64rreOI6j0umN8_ZHpcer4VuJbdVCUO3hEdB795kEadyp7GhNdw2Fy5lEFO', code, 'http://192.168.0.6:5000/callback')
    user_info = auth0_users.userinfo(token['access_token'])
    #session['profile'] = json.loads(user_info)
    print(json.loads(user_info))
    return json.loads(user_info)

@app.route('/')
def hello():
    return 'Server is working'

if __name__ == '__main__':
       app.secret_key = 'A0Zr98j/3yX R~XHH!jmN]LWX/,?RT'
       app.run(host="0.0.0.0")
