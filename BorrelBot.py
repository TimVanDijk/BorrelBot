import serial
import time
import socket
import sys
from threading import Thread
from threading import Lock
#External dependencies: pySerial

print("""
888888b.                                    888 888888b.            888    
888  "88b                                   888 888  "88b           888    
888  .88P                                   888 888  .88P           888    
8888888K.   .d88b.  888d888 888d888 .d88b.  888 8888888K.   .d88b.  888888 
888  "Y88b d88""88b 888P"   888P"  d8P  Y8b 888 888  "Y88b d88""88b 888    
888    888 888  888 888     888    88888888 888 888    888 888  888 888    
888   d88P Y88..88P 888     888    Y8b.     888 888   d88P Y88..88P Y88b.  
8888888P"   "Y88P"  888     888     "Y8888  888 8888888P"   "Y88P"   "Y888

""")

#Used to kill the arduinoHandler thread.
kill_received = False

#Queue of orders that still need to be forwarded to the arduino.
queue = []

#Try to find an arduino and then create a connection.
#Can be done using a for loop, but we don't have many objects anyway
try: 
	ser = serial.Serial('/dev/ttyUSB0', 9600)
except serial.SerialException:
	try:
		ser = serial.Serial('/dev/ttyUSB1', 9600)
	except serial.SerialException:
		try:
			ser = serial.Serial('/dev/ttyUSB2', 9600)
		except serial.SerialException:
			print("[-] - Cannot find arduino.")
			print("[-] - Exiting...")
			sys.exit(1)
print("[+] - Arduino found!")
print("[*] - Waiting for the connection to be established...")			
time.sleep(5) #Leave this out and we get weird readings from the serial.
print("[+] - Done!")

#Checks the queue for orders and forwards them to the arduino
def arduinoHandler(lock):
	global queue
	global ser
	global kill_received

	while not kill_received:
		if queue != []:
			lock.acquire()
			msg = queue[0]
			queue = queue[1:]
			lock.release()
			print("[*] - Sending message: " + msg)
			if True:
				ser.write(msg.encode())	
				#Wait for the arduino to respond. Then proceed to next order.
				while not kill_received:
					if ser.readline() != "":
						print("[+] - Drink served!")
						break
		else:
			#Wait 3 seconds before checking again
			time.sleep(3)
	print("[*] - arduinoHandler thread stops now.")

def main():
	global queue
	global kill_received

	try:
		#Mutex for accessing the drink queue
		lock = Lock()
		t = Thread(target=arduinoHandler(lock))
		t.start()
		
		#Used for communication with the webinterface. 
		s = socket.socket()
		host = "localhost"
		port = 12345
		s.bind((host, port))
		s.listen(5)

		while not kill_received:
			c, addr = s.accept()
			data = c.recv(1024)
			c.close()
			if data:
				print("Received order: " + str(data))
				amounts = data.split("-")
				msg = ""
				for amount in amounts:
					msg += amount.zfill(4)
				msg += "#"
				lock.aquire()
				queue.append(msg)
				lock.release()
			
	except KeyboardInterrupt:
		print("\n[*] - Received KeyboardInterrupt.")
		print("[*] - Telling arduinoHandler thread to stop.")
		#Tell the arduinoHandler to stop
		kill_received = True
		try:
			lock.release()#In het geval dat de main thread de lock had tijdens de keyboardinterrupt
		except ThreadError:
			pass
		#Close the socket
		if s:
			s.close()

if __name__ == "__main__":
	main()
