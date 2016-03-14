import serial
import time
import socket
import sys

try: 
	ser = serial.Serial('/dev/ttyUSB0', 9600)
except serial.SerialException:
	try:
		ser = serial.Serial('/dev/ttyUSB1', 9600)
	except serial.SerialException:
		try:
			ser = serial.Serial('/dev/ttyUSB2', 9600)
		except serial.SerialException:
			print("Cannot find arduino :(")
			print("Exiting...")
			sys.exit(1)

time.sleep(5) # Needed to open connection
s = socket.socket()
host = "localhost"
port = 12345
s.bind((host, port))

s.listen(5)

while True:
	c, addr = s.accept()
	data = c.recv(1024)
	c.close()
	if data:
		print "Received order: " + str(data)
		amounts = data.split("-")
		msg = ""
		for amount in amounts:
			msg += amount.zfill(4)
		msg += "#"
		print("Sending message: " + msg)
		ser.write(msg.encode())	