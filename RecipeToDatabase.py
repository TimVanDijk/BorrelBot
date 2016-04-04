import MySQLdb
import os
import json

cNames = []
querys = []


db = MySQLdb.connect(
	host = "localhost",
	user = "root",
	passwd = "pass123",
	db = "borrelbot")

cur = db.cursor()

for file in os.listdir("./recipes"):
	if file.endswith(".json"):
		cNames.append(file[:-5]) #Remove the trailing .json

for cName in cNames:
	with open("./recipes/" + cName + ".json") as cFile:
		cData = [x.replace("'", "") for x in json.load(cFile)]
		print(cName)

		if len(cData) == 1:
			query = (	"INSERT INTO cocktails " 
						"(name, picture, ingr_1, amnt_1)"
						" VALUES ('" + cName + "', " + "'/images/" + cName + ".jpg', '" + cData[0] + "', '30')")

		if len(cData) == 2:
			query = (	"INSERT INTO cocktails " 
						"(name, picture, ingr_1, amnt_1, ingr_2, amnt_2)"
						" VALUES ('" + cName + "', " + "'/images/" + cName + ".jpg', '" + cData[0] + "', '30'"
						", '" + cData[1] + "', '30')")

		if len(cData) == 3:
			query = (	"INSERT INTO cocktails " 
						"(name, picture, ingr_1, amnt_1, ingr_2, amnt_2, ingr_3, amnt_3)"
						" VALUES ('" + cName + "', " + "'/images/" + cName + ".jpg', '" + cData[0] + "', '30'"
						", '" + cData[1] + "', '30'" + ", '" + cData[2] + "', '30')")

		if len(cData) == 4:
			query = (	"INSERT INTO cocktails " 
						"(name, picture, ingr_1, amnt_1, ingr_2, amnt_2, ingr_3, amnt_3, ingr_4, amnt_4)"
						" VALUES ('" + cName + "', " + "'/images/" + cName + ".jpg', '" + cData[0] + "', '30'"
						", '" + cData[1] + "', '30'" + ", '" + cData[2] + "', '30'"  + ", '" + cData[3] + "', '30')")
		try:
			print(query)
			querys.append(query)
		except:
			pass

querys = list(set(querys))
for query in querys:
	cur.execute(query)
	
db.commit()
db.close()