# OvOEnergy
Scripts for my smarthome web dashboard to show weekly rolling energy use.




Installation:
1: Have MySQL Database with 
  Database name "OvoEnergy",
    Table called "Electricity"
      Rows: 
        name: dateandtime  Type: datetime
        name: consumption  Type: double
        name: unit         Type: varchar
        name: id           Type: int (Auto Increment)
    Table called "Gas"
      Rows: 
        name: dateandtime  Type: datetime
        name: consumption  Type: double
        name: unit         Type: varchar
        name: id           Type: int (Auto Increment)
        
2:Update Details in OvoEnergy.php and GetOvoData.php where there are *****#

3:GetOvoData.php can be used to backfill the last few days by first using the uncommented line and changing the date and running the script
4:Once you have backfilled the database comment out that line and replace it with the line below, then set that script on a daily Cron job.
