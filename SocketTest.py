import socket

s = socket.socket()
host = "localhost"
port = 12345
s.bind((host, port))

s.listen(5)

while True:
	c, addr = s.accept()
	data = c.recv(1024)
	if data:
		print data
	c.close()