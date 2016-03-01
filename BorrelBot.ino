/*
 * Arduino char parrot for send.py
 */

#include <LiquidCrystal.h>
#include <math.h>
//LCD
LiquidCrystal lcd = LiquidCrystal(8, 9, 4, 5, 6, 7);

// Full recipe available
bool recipe = false;
// current transmitting pumpNumber (-2 = nothing, -1 = got 'p', 0123= pumpNumber)
int pNum = -2;
// time per pump (-2 = not set yet, -1 = got 'm', 0 = keep off, 1+ = pupmTime)
int pTime[4] = {-2, -2, -2, -2};
int pTimeTmp[3] = {-1, -1, -1};
int pTimePos = 0;

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
 * (P: 'x' -> begin again)
 * P = Pi, A = Arduino
 * return pumpNum (-2: reset, -1: recieved 'p', 0-3: pumpNum)
 */
int listenForPump(){
  while (!Serial.available()) {}
  // get char
  int tmp = Serial.read();
  if (char(tmp) == 'x'){
    // something went wrong
    return -2;
  }
  if (pNum == -2 && char(tmp) != 'p'){
    // Something went wrong
    Serial.print('x');
    return -2;
  }
  Serial.print(char(tmp));
  if (char(tmp) == 'p') {
    return -1;
  }
  while (!Serial.available()) {}
  if (Serial.read() == 'k') {
    Serial.print(int(char(tmp) - '0'));
    return int(char(tmp) - '0');
  }
}

/*
 * get Pump timer
 * (P: 'm', A: 'm', P: '00000-99999' A: '00000-99999' P:'k/s')
 * (P: '00000-99999' A: '00000-99999' => P '0', P '4', ..., A: '04...')
 * (P: 'x' -> begin again)
 * (k: following pump. s: start pumping.)
 * P = Pi, A = Arduino
 * return pumpTime (-2: reset, -1: recieving, 0-99999: pumpTime)
 */
int listenForTime(){
  while (!Serial.available()) {}
  // get char
  int tmp = Serial.read();
  if (char(tmp) == 'x') {
    // something went wrong
    pTimePos = 0;
    pNum = -2;
    return -2;
  }
  if (pTime[pNum] == -2 && char(tmp) != 'm'){
    // Something went wrong
    Serial.print('x');
    pTimePos = 0;
    pNum = -2;
    return -2;
  }
  Serial.print(char(tmp));
  if (char(tmp) == 'm') {
    return -1;
  }
  if (char(tmp) == 'k' || char(tmp) == 's') {
    if (pTime[pNum] == -2) {
      // Something went wrong
      Serial.print('x');
      pTimePos = 0;
      pNum = -2;
      return -2;
    }
    // convert number
    int ret = pTimeTmp[(sizeof(pTimeTmp)/sizeof(pTimeTmp[0]))-1];
    //convert array into int
    for (int i = 0; i < (sizeof(pTimeTmp)/sizeof(pTimeTmp[0]))-1; i++) {
      int add = int(int(pow(10, (sizeof(pTimeTmp)/sizeof(pTimeTmp[0]))-(i+1))) * pTimeTmp[i]);
      ret += add;
    }
    // reset stuff
    pTimePos = 0;
    pNum = -2;
    //maybe start
    if (char(tmp) == 's') {
      recipe = true;
    }
    Serial.println(ret);
    return ret;
  }
  // got a number
  pTimeTmp[pTimePos] = int(char(tmp) - '0');
  pTimePos++;
  if (pTimePos > (sizeof(pTimeTmp)/sizeof(pTimeTmp[0]))){
    Serial.print('x');
    return -2;
  }
  //ASCII to int, idk if it works...
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
    delay(1000);
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
