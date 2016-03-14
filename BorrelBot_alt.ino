#include <AFMotor.h>

AF_DCMotor motor1(1);
AF_DCMotor motor2(2);
AF_DCMotor motor3(3);
AF_DCMotor motor4(4);

int pTime[4] = {-1, -1, -1, -1};
int pIndex = 0;

int scalar = 1;

void setup() {
	Serial.begin(9600);
	motor1.setSpeed(255);
	motor2.setSpeed(255);
	motor3.setSpeed(255);
	motor4.setSpeed(255);
	motor1.run(RELEASE);
	motor2.run(RELEASE);
	motor3.run(RELEASE);
	motor4.run(RELEASE);
}

bool ready() {
	return pTime[0] != 0 and pTime[1] != 0 and pTime[2] != 0 and pTime[3] != 0;
}

void reset() {
	for (int i = 0; i < 4; i++) {
		pTime[i] = -1;
	}
	Serial.println("d"); //d(one)
}

void loop() {
	if (ready()) {

		//Debug. Remove in final version
		Serial.print("making drink: ")
		for (int i = 0; i < 4; i++) {
			Serial.print(pTime[i]);
			Serial.print(" ");
		}
		serial.println("");
		//End debug

		//TODO: Make this run in parallel
		motor1.run(FORWARD);
		delay(scalar * pTime[0]);
		motor2.run(FORWARD);
		delay(scalar * pTime[1]);
		motor3.run(FORWARD);
		delay(scalar * pTime[2]);
		motor3.run(FORWARD);
		delay(scalar * pTime[3]);

		reset();
	}	

	if (Serial.available()) {
		String order = Serial.readStringUntil('#');

		//Incomming message is to set selected pump
		if (order[0] == 'p') {
			pIndex = order[1] - '0';
		}

		//Incomming message is to set pump time.
		if (order[0] == 'm') {
			String temp = "";
			for (int i = 1; i < order.length(); i++) {
				if (isDigit(order.charAt(i))) {
					temp += order.charAt(i);
				}
			}
			pTime[pIndex] = int(temp);
		}
	}
}