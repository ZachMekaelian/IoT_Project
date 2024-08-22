# **IoT: BG96 to Cloud Setup and Architecture**

The purpose of this README is to guide users through the setup process of connecting the BG96 device to the Azure cloud. By the end, users will be able to send, parse, and store IoT sensor data.

-----------------------------------------

### **Prerequisites**
Before starting the setup process, ensure you have the following software and tools installed:

* **Quectel BG96 Drivers:** Drivers allow your system to recognize and communicate with the BG96 module.
[Download here](https://www.quectel.com/ProductDownload/BG96.html)

* **QCOM_V1.6:** The software tool for configuring and managing the BG96 module.
[Download here](https://www.quectel.com/download/qcom_v1-6)

* **Certificate for SSL/TLS:** 
Scroll down and download the "Baltimore CyberTrust Root" certificate under "Root Certificate Authorities"
[Download here](https://learn.microsoft.com/en-us/azure/security/fundamentals/azure-ca-details?tabs=root-and-subordinate-cas-list#certificate-downloads-and-revocation-lists))

* **JSON Libraries:** If working in a programming environment and you intend to structure sensor data in JSON format, you may need appropriate libraries.
  **1.** For C: Download cJSON
  **2.** For Python: The JSON library is pre-installed 
  **3.** For other languages, find the relevant JSON library for your environment.

-----------

### ***Step 1: Create an Azure IoT Hub instance***

Instructions:

**Login to Azure Portal:**

Open your web browser and navigate to the Azure Portal.
Sign in with your Azure credentials.
![image](https://github.com/ZachMekaelian/IoT/assets/63831829/295ffe75-4ccc-43f4-b9f3-101629207a6f)

**Navigate to IoT Hub:**

In the left sidebar, click on "Create a Resource".
In the search bar, type "IoT Hub" and select it from the dropdown list.
![image](https://github.com/ZachMekaelian/IoT/assets/63831829/04aff9d2-08d2-488b-9056-ea4802606aec)


**Create IoT Hub:**

Click on the "Create" button.
Fill in the required fields such as Subscription, Resource Group, Region, and IoT Hub Name.
Review other settings and adjust if necessary.
Click the "Review + Create" button at the bottom. Once validation passes, click "Create".
![image](https://github.com/ZachMekaelian/IoT/assets/63831829/e318819d-7d1b-4bc8-bd39-85deec01739a)

--------------

### ***Step 2: Register your BG96 device with the IoT Hub***
To allow your device to communicate with the Azure IoT Hub, you need to register it.

Instructions:

**Go to your IoT Hub:**

From the main dashboard of the Azure Portal, navigate to your newly created IoT Hub.

**Add a device:**
In the IoT Hub navigation pane, click on "IoT devices".
Click on the "New" button to add a device.
Enter a unique Device ID for your BG96 device.
Click "Save".
![image](https://github.com/ZachMekaelian/IoT/assets/63831829/ba4f7921-411a-40ac-8356-973fa15054ba)

**Azure CLI:** 
This is the command-line interface for managing Azure resources. While logged into your account, Click the "Cloud Shell" button at the top of your screen.

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/30ed0917-dbcb-4bc7-b063-625d8cf448b6)

**Once the shell is loaded use the following commands to enable:**

Set your Azure subscription (replace <Subscription_Id> with your actual subscription ID):

``az account set --subscription <Subscription_Id>``

-----------------------

### ***Step 3: Configure your BG96 device***
Now, with your device registered on the Azure IoT Hub, you need to configure the BG96 device to communicate with the hub.

Instructions:

**Gather Connection Details:**

Back in the Azure portal, under your IoT device settings, note down the "Hostname", "Device ID", and "Primary Key".
![image](https://github.com/ZachMekaelian/IoT/assets/63831829/ba40f3a0-9943-4bb1-bc70-0d915a2f5c7e)

------------------------

### ***Step 4: Device Setup and Data Communication***
With everything set up, you can now start sending and receiving data between your BG96 device and the Azure cloud. Using QCOM_V1.6 set use AT commands to enable HTTPS message protocol to allow telemetry to the Azure cloud via mobile internet connection. 

Instructions:

**Set up the BG96 module to send data to Azure over a cellular network using HTTPS.**

Input the following commands:

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/246826fe-e3f4-4933-a057-807fe202aa48)

------------------------------

### ***Step 5: Security - Ensuring a Secure Connection Between BG96 and Azure***

Securing the communication between your BG96 device and the Azure IoT Hub is critical for the integrity and privacy of your data. This section details how to set up SSL/TLS and authenticate your device using a certificate.

Instructions:

**SSL/TLS Configuration:**
Download the Certificate: If you haven't already, download the "Baltimore CyberTrust Root" certificate from this link.
[Download here](https://learn.microsoft.com/en-us/azure/security/fundamentals/azure-ca-details?tabs=root-and-subordinate-cas-list#certificate-downloads-and-revocation-lists))

**Upload Certificate to BG96:**

Open the QCOM_V1.6 software and enter the appropriate AT command to upload the downloaded "Baltimore CyberTrust Root" certificate to the BG96 device.
``AT+QFUPL="BaltimoreCyberTrustRoot.crt"``

**Enable SSL/TLS on BG96:** 

Use the following AT command to enable SSL/TLS for the connection to Azure.
``AT+QSSLCFG="sslversion",1,4``

**Authentication:**

Create a Device Identity: If you haven't already, create a unique device identity for your BG96 in Azure and note down the Device ID and Primary Key.

**Configure Device ID and Primary Key on BG96:**

Enter the following AT commands to set the Device ID and Primary Key for authentication.
``AT+UAUTH=1,"DeviceId=<Your_Device_ID>","PrimaryKey=<Your_Primary_Key>"``

**Set BG96 to Use the Certificate for Authentication:** 

Issue the following AT command to specify that the "Baltimore CyberTrust Root" certificate should be used for the SSL/TLS session.
``AT+USECSSL=1,"root_ca=BaltimoreCyberTrustRoot"``

**Test the Configuration:**

Run a test AT command to verify that SSL/TLS is correctly configured and the device can authenticate to Azure.
``AT+UTESTSSL``

----------------------------

### ***Step 6: Download Code from GitHub***
Download the code repository to help with your IoT setup.

Instructions:
Open your web browser and navigate to GitHub Repo.

Click on the 'Code' button and then select 'Download ZIP', or clone the repository using Git if you have it installed.

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/a248e6e5-1c29-4906-891b-e8bfe7370eda)

--------------------------

### ***Step 7: Create an Azure App Service***
Create an Azure App Service to host the webpage for data display.

Instructions:
Log in to the Azure Portal.

In the left sidebar, click on "Create a Resource".

In the search bar, type "App Service" and select it from the dropdown list.

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/bb85c65c-ab19-401b-b329-7d24d371b620)

Click on the "Create" button.

Fill in the required fields such as Subscription, Resource Group, and App Service Name.

Review other settings and adjust if necessary.

Click "Review + Create" and then "Create" after validation passes.

-------------------------

### ***Step 8: Transfer Files via SSH***
Transfer your GitHub downloaded code to your Azure App Service through SSH.

Instructions:
In the Azure Portal, navigate to your App Service.

Under "Development Tools", click on "SSH".

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/3d359174-a46e-449d-a6c4-6f75dfffa8ba)

An SSH session will start in your web browser.

Use SCP commands to upload your files to the App Service. Here's an example:
``scp -r /path/to/your/local/code <username>@<app_name>.azurewebsites.net:/path/on/server/``

Replace /path/to/your/local/code with the path to the GitHub repository you downloaded, <username> with your Azure username, and <app_name> with the name you gave your App Service.

------------------------------------------------

### ***Step 9: Establish Webhook Event***
Navigate to your IoT Hub and select 'Events' in the left hand drop down menu:

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/f253103d-4991-4d22-903d-858642378a2b)

Scroll down and select 'Web Hook' in the menu below:

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/d3dd845f-fe82-43ef-a7b6-ed173301b119)

Give your Event a name, and select IoT hub as your topic type along with the resource and topic name associated with the IoT hub instance you are using. Select the 'Endpoint Type' drop down menu and select 'Web Hook':

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/187fe4d7-c8e8-47eb-b402-a57cbddb4efc)

Under 'Endpoint' select 'Configure an endpoint'. Under 'Subscriber Endpoint' enter the url associated with your 'endpoint.php' script in the wwwroot folder. The url should be in the format 'https://(yourwebappname).azurewebsites.net/endpoint.php':

![image](https://github.com/ZachMekaelian/IoT/assets/63831829/299a57cf-11f5-4b71-93be-7b58a1763a89)

-----------------------

### ***Step 10: Testing - Verify the Entire Setup***
Data Verification:
Monitor Incoming Data:
Since the parser logic is already deployed in the cloud, monitor the incoming data to Azure using your Azure portal or any other monitoring tools you've set up.
Check Data in Azure:
Navigate to your Azure database and verify that the data has been inserted correctly. The parser logic should automatically process the incoming data packets.
Data Transfer:
Receive Actual TPS Package:

Once your BG96 is set up, it should receive a TPS package from the IoT BLE device.
Automatic Parsing:

The cloud-based parser should automatically parse the incoming TPS package and insert the parsed data into your Azure database.
Insertion Verification:

Navigate to your Azure database and confirm that the parsed data has been inserted correctly.
-- Query to verify data
``SELECT * FROM Data WHERE TransmitterID = <Your_Transmitter_ID>;``
Implement Error Handling:
Make sure that your cloud-based parser has adequate error-handling routines to capture and log any issues that occur during data transfer.
Validate the Data:

Finally, check your Azure database to ensure that the data was correctly received and stored. If possible, compare the stored data against the raw data sent from the BG96 to ensure integrity.

Conclusion:
Your BG96 device is now successfully connected to Azure, your code is hosted on an Azure App Service, and you're set to scale, monitor, and manage your IoT solutions.

Sources: 
Quickstart: Send telemetry from a device to an IoT hub and monitor it with the Azure CLI:
(https://learn.microsoft.com/en-us/azure/iot-hub/quickstart-send-telemetry-cli)
Quectel_BG96_HTTP(S)_AT_Commands_Manual_V1.0
(https://www.quectel.com/download/quectel_bg96_https_at_commands_manual_v1-0)
Control access to IoT Hub:
(https://learn.microsoft.com/en-us/azure/iot-hub/iot-hub-devguide-security#use-shared-access-signatures-sas)

