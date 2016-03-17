import serial
import time
import socket
import sys
from threading import Thread

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
			#sys.exit(1)
print("Arduino found. Waiting for the connection be to established...")			
time.sleep(5) # Needed to open connection
print("Done!")

kill_received = False

queue = []

def arduinoHandler():
	global queue
	global ser
	global kill_received

	while not kill_received:
		print(queue)
		if queue != []:
			msg = queue[0]
			queue = queue[1:]
			print("Sending message: " + msg)
			time.sleep(5)
			if False:
				ser.write(msg.encode())	
				while not kill_received:
					inp = ser.readline()
					if inp != "":
						print(inp)
						break;
		else:
			time.sleep(3)

def main():
	try:
		t = Thread(target=arduinoHandler)
		t.start()
		
		s = socket.socket()
		host = "localhost"
		port = 12345
		s.bind((host, port))
		s.listen(5)

		global queue
		global kill_received

		while not kill_received:
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
				queue.append(msg)
			
	except KeyboardInterrupt:
		print("\nReceived KeyboardInterrupt")
		global kill_received
		kill_received = True
		if s:
			s.close()

if __name__ == "__main__":
	main()

