/*
 * Arduino char parrot for send.py
 */

#include <LiquidCrystal.h>
//LCD
LiquidCrystal lcd = LiquidCrystal(8, 9, 4, 5, 6, 7);

// Full recipe available
bool recipe = false;
// current transmitting pumpNumber (-2 = nothing, -1 = got 'p', 0123= pumpNumber)
int pNum = -2;
// time per pump (-2 = not set yet, -1 = got 'm', 0 = keep off, 1+ = pupmTime)
int pTime[4] = {-2, -2, -2, -2};

void setup(){
  //LCD Hello
  lcd.begin(16,2);
  lcd.print("Hello!");
  //Connection to Pi
  Serial.begin(9600);
}

/*
 * get Pump number
 * (P: 'p', A: 'p', P: '0/1/2/3' A: '0/1/2/3' P:'k')
 * P = Pi, A = Arduino
 * 
 * (P: 'x' -> begin again)
 */
int listenForPump(){
  if (Serial.available()) {
    // get char
    int tmp = Serial.read();
    if (char(tmp) == 'x')
      // something went wrong
      return -2;
    if (pNum == -2 && char(tmp) != 'p'){
      // Something went wrong
      Serial.print('x');
      lcd.print('x');
      return -2;
    }
    Serial.print(char(tmp));
    lcd.print(char(tmp));  
    if (char(tmp) == 'p') {
      return -1;
    }
    //ASCII to int, idk if it works...
    return (tmp - '0');
  }
}

/*
 * get Pump timer
 * 
 */
int listenForTime(){
  if (Serial.available()) {
    // ...
  }
}

void loop(){
  if (!recipe) {
    if (pNum < 0) { // not valid yet
      pNum = listenForPump();
    } else if (pTime[pNum] < 0) { // not valid yet
      pTime[pNum] = listenForTime();
    }
    if (pTime[0] >= 0 && pTime[1] >= 0 && pTime[2] >= 0 && pTime[3] >= 0) {
      // got everything -> start mixing!
      // alternatively Pi sends something at the end of listenForTime.
      recipe = true;
    }
    
  } else {
    //Start mixing.
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Making some drinks.");
    delay(1000);
    // reset everything
    recipe = false;
    pNum = -2;
    for (int i = 0; i < 4; i ++){
      pTime[i] = -2;
    }
  }
  delay(500); // only needed for LCD
}
