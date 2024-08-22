CREATE TABLE IF NOT EXISTS Users (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    First_Name TEXT,
    Last_Name TEXT,
    Username TEXT,
    Password TEXT
);

CREATE TABLE IF NOT EXISTS Data (
    UserID INTEGER,
    Header INTEGER,
    TransmitterID INTEGER,
    Pressure INTEGER,
    Temperature INTEGER,
    BatteryVoltage INTEGER,
    RSSI INTEGER,
    FOREIGN KEY (UserID) REFERENCES Users (ID)
);