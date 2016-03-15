#include <AFMotor.h>

AF_DCMotor motor1(1);
AF_DCMotor motor2(2);
AF_DCMotor motor3(3);
AF_DCMotor motor4(4);

int pTime[4] = {-1, -1, -1, -1};
int pIndex = 0;

int scalar = 200;

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
  return pTime[0] != -1 and pTime[1] != -1 and pTime[2] != -1 and pTime[3] != -1;
}

void reset() {
  for (int i = 0; i < 4; i++) {
    pTime[i] = -1;
  }
  Serial.println("Done :)");
}

void loop() {
  if (ready()) {
    //TODO: Make this run in parallel
    motor1.setSpeed(255);
    motor1.run(FORWARD);
    delay(scalar * pTime[0]);
    motor1.run(RELEASE);
    motor2.setSpeed(255);
    motor2.run(FORWARD);
    delay(scalar * pTime[1]);
    motor2.run(RELEASE);
    motor3.setSpeed(255);
    motor3.run(FORWARD);
    delay(scalar * pTime[2]);
    motor3.run(RELEASE);
    motor4.setSpeed(255);
    motor4.run(FORWARD);
    delay(scalar * pTime[3]);
    motor4.run(RELEASE);
    
    reset();
  }

  /*
   * get a string with 4 numbers. "1111222233334444"
   */
  if (Serial.available()) {
    String order = Serial.readStringUntil('#');
    for (int i = 0; i < 4; i++){
      int tmpTime = 0;
      int mult = 1;
      for (int j = 3; j >= 0; j--) {
        int add = int(char(order[(i*4)+j]) - '0') * mult;
        mult *= 10;
        tmpTime += add;
      }
      pTime[i] = tmpTime;
    }
  }
}
