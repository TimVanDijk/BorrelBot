#include <AFMotor.h> // Pump library

// get the 4 motors/pumps
AF_DCMotor motorA(1);
AF_DCMotor motorB(2);
AF_DCMotor motorC(3);
AF_DCMotor motorD(4);

// put them in an array for easy access
AF_DCMotor motor[4] = {motorA, motorB, motorC, motorD};

// holds the time in milliliters per pump.
long pTime[4] = {-1, -1, -1, -1}; // -1 = not set; 0 = don't run; 1+ = milliliters
// If the pumps should run in reverse
boolean reverse = false;
// factor for converting pTime into actual time
long scalar[4] = {1000,1000,1000,1000};
// base time a pump has to run before anything happens
long constant[4] = {2000,2000,2000,2000};

// stores the return value of millis() to measure time
unsigned long beginTime = 0;
// wil be set to millis() - beginTime in a loop.
unsigned long passedTime = 0;

/*
 * standard Arduino stuff
 */
void setup() {
  Serial.begin(9600); // open communication to Pi
  for (int i = 0; i < 4; i++){ // set all motors to full speed but keep them turned off.
    motor[i].setSpeed(255);
    motor[i].run(RELEASE);
  }
}

/*
 * returns wether all pump-speeds have been set
 */
bool ready() {
  return pTime[0] != -1 and pTime[1] != -1 and pTime[2] != -1 and pTime[3] != -1;
}

/*
 * reset everything to default and send a friendly message to the Pi
 * informing the Pi that we can recieve and execute the next order.
 */
void reset() {
  reverse = false;
  for (int i = 0; i < 4; i++) {
    pTime[i] = -1;
  }
  beginTime = 0;
  passedTime = 0;
  Serial.println("Done :)");
}

/*
 * main loop
 * standard Arduino stuff
 */
void loop() {
  if (ready()) { // start/continue pumping
    if (beginTime == 0) { // need to start/init;  not clever
      beginTime = millis(); // danger: overflow every 50 days
      for (int i = 0; i < 4; i++){ // start all the pumps
        if (pTime[i] > 0) { // if necessary
          motor[i].setSpeed(255);
          if (!reverse){ // direction
            motor[i].run(FORWARD);
          } else {
            motor[i].run(BACKWARDS);
          }
        }
      }
    } else { // continue; update passedTime
      passedTime = millis() - beginTime; // overflow every 50 days
    }
    // pump check
    int donePumps = 0; // number of pumps where passedTime exceeded pTime
    for (int i = 0; i < 4; i++){
        if (pTime[i] > 0 && (scalar[i] * pTime[i]) + constant[i] < passedTime) { // Pump ran long enough
          motor[i].run(RELEASE);
          pTime[i] = 0; // won't be stopped again
        }
        if (pTime[i] <= 0) { // pump has been stopped
          donePumps++;
        }
      }
    if (donePumps == 4){ // all pumps are done
      reset();
    }
  }

  /*
   * get a string with 4 numbers; time for pumps. ex: "0001002203334444" -> pTime = {1, 22, 333, 4444}
   * alternativerly rv to reverse pumps
   */
  if (Serial.available()) {
    String order = Serial.readStringUntil('#');
    if (order[0] == 'r' && order[1] == 'v'){ // reverse
      ptime[0] = 50; // Find the right constants for this by experimentation
      ptime[1] = 50;
      ptime[2] = 50;
      ptime[3] = 50;
      reverse = true;
    } else { // forward
      for (int i = 0; i < 4; i++){
        // what follows is a probably overcomplicated way of converting a part of a string into a number
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
}
