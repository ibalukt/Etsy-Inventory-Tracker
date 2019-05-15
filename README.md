# Integrated_Project
Etsy Inventory Tracker

 1. What this project is:
  This projects is a small inventory tracking tool that syncs with the Etsy API in order to pull a shop owner's active Etsy listings. These listings are then stored in the database and compared against the current on-hand quantities of the shop owner's physical inventory to make inventory management a more simple process. This application also allows the user to take items out of their main inventory to create off-site inventories so that they can manage items that they may be selling locally.
  
2. Why this project could be useful:
  Many Etsy shop owners are creative people running small businesses. They have to wear multiple hats in order to keep their business afloat. The less time they can spend having to try to keep their inventory up to date, the more time they can spend creating.

3. How you could build your own personalized version of this project:
  Currently, this application was designed around my wife's needs and it only supports one user. If you wanted to sync this application with your own shop, you would need to request an API key from the Etsy developers and then add yourself or the shop owner as a provisional user. Once that is complete, you put your API key and other oauth credentials in the ini file. There is a script to generate the database in my project documentation. Paste this into MySql Workbench to generate the database and then you would be good to go. Put your db connection variables in the .ini file and launch the application.
  
If you need help setting up this project of have questions feel free to contact me at : ibalukt@dunwoody.edu.
