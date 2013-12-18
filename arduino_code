//ARDUINO 1.0+ ONLY
//ARDUINO 1.0+ ONLY
#include <Ethernet.h>
#include <EthernetClient.h>
#include <SPI.h>


////////////////////////////////////////////////////////////////////////
//CONFIGURE
////////////////////////////////////////////////////////////////////////
//byte server[] = {5, 135, 188, 103}; 

byte server[] = {213, 186, 33, 87}; 
// 213.186.33.87 / we love the net ip address
// 88, 190, 253, 247 / albertine meunier ip address

//The location to go to on the server
//make sure to keep HTTP/1.0 at the end, this is telling it what type of file it is
String location = "/elevation/altitude.php?user=2 HTTP/1.0";

// if need to change the MAC address (Very Rare)
byte mac[] = { 0x00, 0xAA, 0xBB, 0xCC, 0xDE, 0x02 };
////////////////////////////////////////////////////////////////////////

EthernetClient client;



char inString[32]; // string for incoming serial data
int stringPos = 0; // string index counter
boolean startRead = false; // is reading?
int led = 5;
int inPin = 7;
int val = 0;
int initialisation = 1;
int firstTime = 1;
int cur_alt = 0;
int delta_alt;
int mv;



#define DIR_PIN 2
#define STEP_PIN 3


void setup()
{
  Serial.begin(9600);
  pinMode(DIR_PIN, OUTPUT); 
  pinMode(STEP_PIN, OUTPUT); 
  pinMode(led, OUTPUT);  
  pinMode(inPin, INPUT); 
  Ethernet.begin(mac);
  
 
}

void loop(){
  
    

 if (initialisation) {
   digitalWrite(led, LOW);  
   Serial.println("initialise");
   //val = digitalRead(inPin);
   while (digitalRead(inPin) == LOW) {
       digitalWrite(led, HIGH);  
       rotate(1000, .5); 
       Serial.println("back to 0");
       initialisation = 0;
   }
 }


 Serial.println("get data from API");
 String pageValue = connectAndRead(); //connect to the server and read the output

  //Serial.println(pageValue); //print out the findings.
 
  delay(5000); //wait 10 seconds before connecting again
  }

 
String connectAndRead(){
  //connect to the server

  Serial.println("connecting...");
    if (firstTime) {
      location = "/elevation/altitude.php?user=2&init=1 HTTP/1.0";
    }else {
       location = "/elevation/altitude.php?user=2&init=0 HTTP/1.0";
    }
    Serial.println(location);

  //port 80 is typical of a www page
  if (client.connect(server, 80)) {
    Serial.println("connected");
    client.print("GET ");
    client.println(location);
    client.println("Host: we-love-the.net");
    client.println();

    //Connected - Read the page
    return readPage(); //go and read the output
    

  }else{
    return "connection failed";
  }

}

String readPage(){
  //read the page, and capture & return everything between '<' and '>'

  stringPos = 0;
  memset( &inString, 0, 32 ); //clear inString memory

  while(true){

    if (client.available()) {
      char c = client.read();

      if (c == '<' ) { //'<' is our begining character
        startRead = true; //Ready to start reading the part 
      }else if(startRead){

        if(c != '>'){ //'>' is our ending character
          inString[stringPos] = c;
          stringPos ++;

        }else{
          
          //got what we need here! We can disconnect now
        
          startRead = false;
          client.stop();
          client.flush();
          
        // affiche la valeur au format Float. c'est une valeur numérique
          float ret = atof(inString);
          
           
             if (!firstTime) {
               //delta_alt = s - cur_alt;
                //cur_alt = s;
               //s = delta_alt;
                Serial.print(ret,0);
             Serial.println(" return value");
               delta_alt = ret * 1.8;
                  // Serial.print(delta_alt,0);
             //Serial.println(" delta altitude albertine");
               cur_alt = cur_alt + delta_alt;
              mv = delta_alt;
             
              Serial.print(cur_alt);
              Serial.println(" valeur courante ");
                Serial.print(mv);
              Serial.println(" mv ");
            
           }else {
              ret = ret;
               Serial.print(ret,0);
             Serial.println(" metres altitude albertine");
              cur_alt = ret;
              mv = ret;
           }
             
          
          
        
           //Serial.println(firstTime);
         
          rotate(mv*100, .5); 
         delay(10000);   
       
       
         
         Serial.print("et (deconnecting) voila ");
         firstTime = 0;
         
         

        // affiche la  valeur au format String. c'est une valeur de chaine de caractere
        //  Serial.print("la string : ");
        
        return inString;
          

         
        }

      }
    }

  }

}

void fairezero()
{
   val = digitalRead(inPin);
  if (val == HIGH) {
  rotate(0, .5); 
  digitalWrite(led, HIGH);    // turn the LED on by making the voltage LOW
  }
else {
  digitalWrite(led, LOW);  
  rotate(1000, .5); 
 
}
}



void rotate(int steps, float speed){ 
  //rotate a specific number of microsteps (8 microsteps per step) - (negitive for reverse movement)
  //speed is any number from .01 -> 1 with 1 being fastest - Slower is stronger
  int dir = (steps > 0)? HIGH:LOW;
  steps = abs(steps);

  digitalWrite(DIR_PIN,dir); 

  float usDelay = (1/speed) * 70;

  for(int i=0; i < steps; i++){ 
    digitalWrite(STEP_PIN, HIGH); 
    delayMicroseconds(usDelay); 

    digitalWrite(STEP_PIN, LOW); 
    delayMicroseconds(usDelay); 
  } 
} 

void rotateDeg(float deg, float speed){ 
  //rotate a specific number of degrees (negitive for reverse movement)
  //speed is any number from .01 -> 1 with 1 being fastest - Slower is stronger
  int dir = (deg > 0)? HIGH:LOW;
  digitalWrite(DIR_PIN,dir); 

  int steps = abs(deg)*(1/0.225);
  float usDelay = (1/speed) * 70;

  for(int i=0; i < steps; i++){ 
    digitalWrite(STEP_PIN, HIGH); 
    delayMicroseconds(usDelay); 

    digitalWrite(STEP_PIN, LOW); 
    delayMicroseconds(usDelay); 
  } 
}
