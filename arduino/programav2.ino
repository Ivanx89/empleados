#include <MFRC522.h>
#include <WiFiNINA.h>
 
#define SS_PIN 10
#define RST_PIN 9

MFRC522 mfrc522(SS_PIN, RST_PIN); 

char ssid[] = "MiFibra-5210-24G";
char pass[] = "a6EDVWpM";

int status = WL_IDLE_STATUS;

char server[] = "192.168.1.15";

String postData;
String postVariable = "card=";
String tarjetaleida;
WiFiClient client;

void setup() 
{
  Serial.begin(9600);   // Initiate a serial communication
  SPI.begin();      // Initiate  SPI bus
  delay(4);
  mfrc522.PCD_Init();   // Initiate MFRC522
 

  
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
  //Show UID on serial monitor
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
    Serial.print("GET /empleados/getdata.php?");
    client.print("GET /empleados/getdata.php?");     //YOUR URL
    Serial.println(postData);
    client.print(postData);
    client.print(" ");      //SPACE BEFORE HTTP/1.1
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: 192.168.1.15");
    client.println("Connection: close");
    client.println();
  } else {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }

  if (client.connected()) {
    client.stop();
  }
  Serial.println(postData);

  delay(3000);
  

} 
