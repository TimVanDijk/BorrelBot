#include <AFMotor.h>

AF_DCMotor motorA(1);
AF_DCMotor motorB(2);
AF_DCMotor motorC(3);
AF_DCMotor motorD(4);

AF_DCMotor motor[4] = {motorA, motorB, motorC, motorD};

int pTime[4] = {-1, -1, -1, -1};
int pIndex = 0;

int scalar = 200;

unsigned long beginTime = 0;
unsigned long passedTime = 0;

void setup() {
  Serial.begin(9600);
  for (int i = 0; i < 4; i++){
    motor[i].setSpeed(255);
    motor[i].run(RELEASE);
  }
}


bool ready() {
  return pTime[0] != -1 and pTime[1] != -1 and pTime[2] != -1 and pTime[3] != -1;
}

void reset() {
  for (int i = 0; i < 4; i++) {
    pTime[i] = -1;
  }
  beginTime = 0;
  Serial.println("Done :)");
}

void loop() {
  if (ready()) {
    if (beginTime == 0) { // not clever
      // init
      beginTime = millis(); // overflow
      for (int i = 0; i < 4; i++){
        if (pTime[i] > 0) {
          motor[i].setSpeed(255);
          motor[i].run(FORWARD);
        }
      }
    } else {
      passedTime = millis() - beginTime; // overflow
    }
    int donePumps = 0;
    for (int i = 0; i < 4; i++){
        if (pTime[i] > 0 && (scalar * pTime[i]) < passedTime) {
          motor[i].run(RELEASE);
          pTime[i] = 0;
        }
        if (pTime[i] <= 0) {
          donePumps++;
        }
      }
    if (donePumps == 4){
      reset();
    }
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
