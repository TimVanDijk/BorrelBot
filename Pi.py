import serial
# import random
import time

ser = serial.Serial('/dev/ttyUSB2', 9600)
def flushAll():
	ser.flushInput()
	ser.flushOutput()

# Next time is for pump n
# Protocol in-depth in *.ino
def sendPump(n):
	print ("Send: p"+str(n))
	ser.write (("p"+str(n)+"#").encode())
	tmp = ser.readline()
	flushAll()
	print "Recv: " + str(tmp[0])
	if tmp[0] == str(n):
		ser.write("k#".encode())
		print "Send: k"
	else:
		ser.write("x#".encode())
		print "Send: x"
		sendPump(n)
	flushAll()

# time for previously sent pump is n.
# Protocol in-depth in *.ino
def sendMillis(n, s):
	ser.write (("m"+str(n)+"#").encode()) #TODO < 100
	print ("Send: m"+str(n))
	time.sleep(1)
	tmp = ser.readline()
	flushAll()
	print "Recv: " + str(tmp[:len(str(n))])
	if str(n) == tmp[:len(str(n))]:
		if (s == True):
			ser.write ("s".encode())
			print ("Send: s")
		else:
			ser.write ("k#".encode())
			print ("Send: k")
		flushAll()
		time.sleep(0.2)
		tmp = ser.readline()
		print "Recv: " + str(tmp)
	else:
		ser.write("x#".encode())
		print "Send: x"
		sendMillis(n, s)
	flushAll()

# Start of Program
time.sleep(5) # Needed to open connection
# test:
sendPump(0);
sendMillis(230, False);
sendPump(1);
sendMillis(99, False);
sendPump(2);
sendMillis(1, False);
sendPump(3);
sendMillis(0, True);