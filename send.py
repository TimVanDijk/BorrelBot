import serial
import random
import time

ser = serial.Serial('/dev/ttyUSB0', 9600)

time.sleep(5)
while True:
    rand = random.randrange(33, 123);
    msg = chr(rand)
    ser.write(bytes(msg))
    print "sent: " + msg
    tmp = ser.read()
    print "recv: " + tmp
    time.sleep(5)