#include <MFRC522.h>
#include <WiFiNINA.h>
 
#define SS_PIN 10
#define RST_PIN 9
int buzzer = 12;//the pin of the active buzzer

MFRC522 mfrc522(SS_PIN, RST_PIN); 

char ssid[] = "";
char pass[] = "";

int status = WL_IDLE_STATUS;

char server[] = "";

String postData;
String postVariable = "card=";
String tarjetaleida;
WiFiClient client;

void setup() 
{
  Serial.begin(9600);   
  SPI.begin();      
  delay(4);
  mfrc522.PCD_Init();   
  pinMode(buzzer,OUTPUT);//initialize the buzzer pin as an output

  
  while (status != WL_CONNECTED) {
    Serial.print("Intentando conectarse a la red: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, pass);
    delay(10000);
  }

  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());
  IPAddress ip = WiFi.localIP();
  IPAddress gateway = WiFi.gatewayIP();
  Serial.print("IP: ");
  Serial.println(ip);
  Serial.println("Acerque la tarjeta...");
  Serial.println();
  digitalWrite(buzzer,HIGH);
  delay(5);//wait for 1ms
  digitalWrite(buzzer,LOW);
  delay(5);//wait for 1ms
  digitalWrite(buzzer,HIGH);
  digitalWrite(buzzer,LOW);

}
void loop() 
{
  // Look for new cards
  if ( ! mfrc522.PICC_IsNewCardPresent()) 
  {
    return;
  }
  // Select one of the cards
  if ( ! mfrc522.PICC_ReadCardSerial()) 
  {
    return;
  }
  
  String content= "";
  byte letter;
  for (byte i = 0; i < mfrc522.uid.size; i++) 
  {

     content.concat(String(mfrc522.uid.uidByte[i] < 0x10));
     content.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  Serial.print("Lectura : ");
  content.toUpperCase();
  
  tarjetaleida = content.substring(1);

  postData = postVariable + tarjetaleida;





   if (client.connect(server, 80)) {
    Serial.println("connected");
    // Make a HTTP request:
    Serial.print("POST /empleados/getdata.php?");
    client.print("POST /empleados/getdata.php?");    
    digitalWrite(buzzer,HIGH);
      delay(25);//wait for 1ms
      digitalWrite(buzzer,LOW);
    Serial.println(postData);
    client.print(postData);
    client.print(" ");      //SPACE BEFORE HTTP/1.1
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: ");
    client.println("Connection: close");
    client.println();
  } else {
    
    Serial.println("connection failed");
  }

  if (client.connected()) {
    client.stop();
  }
  Serial.println(postData);

  delay(3000);
  

} 
