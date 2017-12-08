import json
import urllib2
import requests
def jsontomd(data,path,name):

    # data1 = json.loads(data)
    data1=data
    # print(data)
    keylist = data1.keys()
    # print(keylist)
    flag=0;
    f= open(name+".md","w+")
    f.write('+++'+'\n')
    for key in keylist:
        if(key!="description"):
            f.write(key + ':'+'"'+str(data1[key])+'"'+'\n')
        else:
            flag=1;
    f.write('+++'+'\n')
    if(flag==1):
        f.write(str(data1["description"])+'\n')
    f.close()

# jsontomd(file.read(),"content")
def site_pages(api_key,user_id):
    data = {"site_api": str(api_key),"username": str(user_id)}
    url = 'http://192.168.0.5:8088/api/v1/index.php/site_data_get'
    headers = {'content-type': 'application/json'}
    response = requests.post(url, data=json.dumps(data), headers=headers)
    return json.loads(response.content)
