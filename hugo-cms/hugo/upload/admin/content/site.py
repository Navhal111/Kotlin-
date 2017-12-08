import config
import json
import urllib2
import requests


# file = open("main.json", "r")
# config.jsontomd(file.read(),"comtent")

data=config.site_pages("cbbeb1126053ae9743a13034458eaae88cf889272d99177b3708663dd1446c374b45593132333435","light@light.com")
# print(data)
page ={}
for pages in data['Home']:
    page[pages['field_label']]=pages['field_name']
    page[pages['field_type']]=pages['field_data']

config.jsontomd(page,"comtent","Home")

for pages in data['Servay']:
    page[pages['field_label']]=pages['field_name']
    page[pages['field_type']]=pages['field_data']

config.jsontomd(page,"comtent","Servay")
