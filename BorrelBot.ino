/*
 * Arduino Pump and Time reciever for Pi.py
 */

#include <LiquidCrystal.h>
#include <math.h>
//LCD
LiquidCrystal lcd = LiquidCrystal(8, 9, 4, 5, 6, 7);

// Full recipe available
bool recipe = false;
// current transmitting pumpNumber (-1 = nothing, 0/1/2/3= pumpNumber)
int pNum = -1;
// time per pump (-1 = not set yet, 0 = keep off, 1+ = pumpTime)
int pTime[4] = {-1, -1, -1, -1};

void setup(){
  //LCD Hello
  lcd.begin(16,2);
  lcd.print("Hello!");
  //Connection to Pi
  Serial.begin(9600);
}

/*
 * get Pump number
 * (P: "p0/1/2/3" A: '0/1/2/3' P:'k')
 * (P: 'x' -> begin again)
 * P = Pi, A = Arduino
 * return pumpNum (-1: reset, 0-3: pumpNum)
 */
int listenForPump(){
  while (!Serial.available()) {}
  // get String
  String tmp = Serial.readStringUntil('#');
  if (tmp[0] == 'p') {
    Serial.println(int(char(tmp[1]) - '0'));
  } else {
    Serial.println("x");
    return -1;
  }
  while (!Serial.available()) {}
  if (Serial.read() == 'k') {
    return int(char(tmp[1]) - '0');
  }
  Serial.println("x");
  return -1;
}

/*
 * get Pump timer
 * (P: 'm000-m999', A: '000-999' P:'k/s')
 * (k: following pump. s: start pumping.)
 * P = Pi, A = Arduino
 * return pumpTime (-1: reset, 0-999: pumpTime)
 */
int listenForTime(){
  while (!Serial.available()) {}
  // get String
  String tmp = Serial.readStringUntil('#');
  if (tmp[0] == 'm') {
    // convert number
    int ret = 0;
    int mult = 1;
    for (int i = strlen(tmp.c_str())-1; i > 0; i--) {
      int add = int(char(tmp[i]) - '0') * mult;
      mult *= 10;
      ret += add;
    }
    Serial.println(ret);
    while (!Serial.available()) {}
    // get String
    String tmp = Serial.readStringUntil('#');
    if (tmp[0] == 'k' || tmp[0] == 's') {
      if (tmp[0] == 's') {
        Serial.println('s');
        recipe = true;
      }
      Serial.println('k');
      pNum = -1;
      return ret;
    }
  }
  Serial.println("x");
  return -1;
}

void loop(){
  if (!recipe) {
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Listen");
    if (pNum < 0) { // not valid yet
      lcd.print("P");
      pNum = listenForPump();
    } else if (pTime[pNum] < 0) { // not valid yet
      lcd.print("M");
      pTime[pNum] = listenForTime();
    } else {
      recipe = true;
    }
  } else {
    //Start mixing.
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print (pTime[0]);
    lcd.print ("     ");
    lcd.print (pTime[1]);
    lcd.setCursor(0,1);
    lcd.print (pTime[2]);
    lcd.print ("     ");
    lcd.print (pTime[3]);
    delay(2000);
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
    // send ready
  }
  delay(500); // only needed for LCD
}
