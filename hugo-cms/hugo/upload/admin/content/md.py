import pypandoc
import json
import toml
import string
# file = open("config.toml", "r")
# # config.jsontomd(file.read(),"comtent")
# data1 = file.read()
# # print(data1)
# # print(pypandoc.convert_text(json.dumps(data1), 'md', 'json'))
# tomalfiledata = toml.loads(data1)
# print(tomalfiledata)
# from nose.tools import raises

file = open("config.json", "r")
# config.jsontomd(file.read(),"comtent")
data1 = file.read()
str1 =string.replace(data1, "'", '"')
data =  json.loads(str1)
# mainjson = dict(data)
# print(data['menu']['main'])
print(data['menu']['main'][0]['name'])	 

tomalfiledata = toml.dumps(data)
# print(tomalfiledata)