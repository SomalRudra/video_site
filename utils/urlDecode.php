<?php
$string = urldecode(
    "RoomID=1169&HeaderTable=SROfferSchedule&HeaderID=30347&TimeFromDis=10%3A00+AM&TimeFrom=10%3A00+AM&TimeToDis=12%3A00+PM&TimeTo=12%3A00+PM&StudentUID34318=34318&Stu34318=1054&C_Edited34318=&C34318=&StudentUID34392=34392&Stu34392=1054&C_Edited34392=&C34392=&StudentUID34313=34313&Stu34313=1054&C_Edited34313=&C34313=&StudentUID34315=34315&Stu34315=1054&C_Edited34315=&C34315=&StudentUID34342=34342&Stu34342=1054&C_Edited34342=&C34342=&StudentUID34365=34365&Stu34365=1054&C_Edited34365=&C34365=&StudentUID34389=34389&Stu34389=1054&C_Edited34389=&C34389=&StudentUID34307=34307&Stu34307=1054&C_Edited34307=&C34307=&StudentUID34296=34296&Stu34296=1054&C_Edited34296=&C34296=&StudentUID34368=34368&Stu34368=1054&C_Edited34368=&C34368=&StudentUID34295=34295&Stu34295=1054&C_Edited34295=&C34295=&StudentUID34300=34300&Stu34300=1054&inclass34300=INCLASS&C_Edited34300=&C34300=%5BInClass%5D+-+&StudentUID34390=34390&Stu34390=1054&C_Edited34390=&C34390=&StudentUID34288=34288&Stu34288=1054&C_Edited34288=&C34288=&StudentUID34393=34393&Stu34393=1054&C_Edited34393=&C34393=&StudentUID34360=34360&Stu34360=1054&C_Edited34360=&C34360=&StudentUID34285=34285&Stu34285=1054&C_Edited34285=&C34285=&StudentUID34352=34352&Stu34352=1054&C_Edited34352=&C34352=&StudentUID34291=34291&Stu34291=1054&C_Edited34291=&C34291=&StudentUID34367=34367&Stu34367=1054&inclass34367=INCLASS&C_Edited34367=&C34367=%5BInClass%5D+-+&StudentUID34369=34369&Stu34369=1054&C_Edited34369=&C34369=&StudentUID34339=34339&Stu34339=1054&C_Edited34339=&C34339=&StudentUID34336=34336&Stu34336=1054&C_Edited34336=&C34336=&StudentUID34303=34303&Stu34303=1054&inclass34303=INCLASS&C_Edited34303=&C34303=%5BInClass%5D+-+&StudentUID34286=34286&Stu34286=1054&C_Edited34286=&C34286=&StudentUID34305=34305&Stu34305=1054&inclass34305=INCLASS&C_Edited34305=&C34305=%5BInClass%5D+-+&StudentUID34386=34386&Stu34386=1054&inclass34386=INCLASS&C_Edited34386=&C34386=%5BInClass%5D+-+&StudentUID34329=34329&Stu34329=1054&inclass34329=INCLASS&C_Edited34329=&C34329=%5BInClass%5D+-+&StudentUID34370=34370&Stu34370=1054&C_Edited34370=&C34370=&StudentUID34326=34326&Stu34326=1054&C_Edited34326=&C34326=&StudentUID34309=34309&Stu34309=1054&C_Edited34309=&C34309=&StudentUID34335=34335&Stu34335=1054&inclass34335=INCLASS&C_Edited34335=&C34335=%5BInClass%5D+-+&StudentUID34316=34316&Stu34316=1054&C_Edited34316=&C34316=&StudentUID34299=34299&Stu34299=1054&C_Edited34299=&C34299=&StudentUID34308=34308&Stu34308=1054&C_Edited34308=&C34308=&StudentUID33177=33177&Stu33177=1054&C_Edited33177=&C33177=&StudentUID34362=34362&Stu34362=1054&inclass34362=INCLASS&C_Edited34362=&C34362=%5BInClass%5D+-+&StudentUID34330=34330&Stu34330=1054&inclass34330=INCLASS&C_Edited34330=&C34330=%5BInClass%5D+-+&StudentUID34391=34391&Stu34391=1054&C_Edited34391=&C34391=&StudentUID34290=34290&Stu34290=1054&inclass34290=INCLASS&C_Edited34290=&C34290=%5BInClass%5D+-+&StudentUID34320=34320&Stu34320=1054&C_Edited34320=&C34320=&StudentUID34334=34334&Stu34334=1054&C_Edited34334=&C34334=&StudentUID34322=34322&Stu34322=1054&C_Edited34322=&C34322=&StudentUID34293=34293&Stu34293=1054&inclass34293=INCLASS&C_Edited34293=&C34293=%5BInClass%5D+-+&StudentUID34338=34338&Stu34338=1054&C_Edited34338=&C34338=&classdate=1%2F14%2F2022&dateFrom=01%2F14%2F2022&newPage=&currentPage=1&pageSize=0&totalPages=1&IsPostBack=True&hShowWithdrawn=False&hShowPhoto=False&accessKey=MfluJbfgT2HxdGcT357xqVI1BKmuFtdLobvENpp67Av1HUzIaZzhREsj5lg5ryTBMxhMitJSV7WYutiGU3kaEwr9CXxxEeJrDM1rhRUziz9ERrN7ryToQHoABIKgff4YGUzkRLKsr425CTlUjzUH&srofferSchedule=30347"
);

$parts = explode("&", $string);

foreach ($parts as $part) {
    print("$part \n");
}