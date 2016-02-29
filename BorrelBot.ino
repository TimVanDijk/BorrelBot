/*
 * Arduino char parrot for send.py
 */

#include <LiquidCrystal.h>

LiquidCrystal lcd = LiquidCrystal(8, 9, 4, 5, 6, 7);

const int ledPin = 12;

void setup(){
  lcd.begin(16,2);
  lcd.print("Hello!");
  Serial.begin(9600);
}

void loop(){
  if (Serial.available()) {
    lcd.clear();
    lcd.setCursor(0,0);
    int tmp = Serial.read();
    Serial.print(char(tmp));
    lcd.print(char(tmp));
    }
  delay(500);
}
