import serial
# import random
import time

ser = serial.Serial('/dev/ttyUSB0', 9600)

# Next time is for pump n
# Protocol in-depth in *.io
def sendPump(n):
	ser.write (bytes('p'))
	tmp = ser.read()
	if (tmp == bytes('p')):
		ser.write(bytes(n))
	else:
		ser.write('x')
		sendPump(n)

# time for previously sent pump is n.
# Protocol in-depth in *.io
def sendMillis(n):
	correct = True
	ser.write (bytes('m'))
	tmp = ser.read()
	if (tmp == bytes('m')):
		for i in xrange(0, len(str(n))):
			correct = False
			ser.write(bytes(str(n)[i]))
			tmp = ser.read()
			if (tmp != bytes(str(n)[i])):
				break;
			correct = True
	else:
		correct = False
	if (False == correct):
		ser.write('x')
		print "Fail"
		sendMillis(n)
	print "Success!"


# Start of Program
time.sleep(5) # Needed to open connection
# test:
sendPump(2);
sendMillis(20);


# Sending random stuff for testing purposes
# while True:
    # rand = random.randrange(33, 123);
    # msg = chr(rand)
    # ser.write(bytes(msg))
    # print "sent: " + msg
    # tmp = ser.read()
    # print "recv: " + tmp
    # time.sleep(5)