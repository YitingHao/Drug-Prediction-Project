# Drug-Prediction-Project

### This shared package includes:
1. Heatmap_Spreadsheet folder

2. Heatmap_Database folder

3. Drug-repo-App

### Heatmap_Spreadsheet folder

* Purpose - Get data from Google Spreadsheet and visualize data in the form of heatmap

* How to use 
> Open heat.html in your browser, enter the URLs of your Gene Spreadsheet and Drug Spreadsheet, click on submit button, and that’s it. You can try the following existed data below and see the result: _https://docs.google.com/spreadsheets/d/1R_8knsLv51BNQaXYzb1zeTSygfIPyLZcu2TQXqMfuHE/edit#gid=0_ as a Gene Spreadsheet and _https://docs.google.com/spreadsheets/d/1R_8knsLv51BNQaXYzb1zeTSygfIPyLZcu2TQXqMfuHE/edit#gid=1336121434_ for Drug Spreadsheet

* Format of your spreadsheet
> The program read data in a specific format. So the data in your spreadsheet needs to follow this format. For reference, please check this template: _https://docs.google.com/spreadsheets/d/1R_8knsLv51BNQaXYzb1zeTSygfIPyLZcu2TQXqMfuHE/edit?usp=sharing_. You **DO NOT** need to copy the exact format. You can change columns. For example, in the template the first column is Gene Name and the second is Gene Weight. You can switch them and you will still get the same result. However, the first row must be the labels and rows must be items. If you want the code to read data from another format, you need to change the original code in _js/heatmap.js_

* HTML file
> Show the front page with two inputs element, one submit button, two hidden tables, and two div elements to hold D3 svg figures 

* js folder
> This includes four files. One is jQuery.js, which is required when you use jQuery. Two plugins sheetrock.js and tabletojson.js are also included. Plugin sheetrock.js have functions to read tables in Google Spreadsheets and show the same tables in table elements. Tabletojson.js is used to switch the table data to json object, which is easier for data manipulation. Heatmap.js file is the main javascript file that use D3 library to visualize data

### Heatmap_Database folder

* Purpose - Get data from database and visualize data in the form of heatmap

* How to use - Select the disease you want and based on your request, it will get data from database (here is local) and then visualize the data.

* HTML file - Includes just a form element that can pass your request to PHP code

* PHP file - The core of this application. The code between <?php ?> establish a connection with database and get relative data. Then pass the data as a json object. The <script> part uses D3 library and draw the heatmap (This D3 code is more concise than the last one because it is much easier to manipulate data when you obtain data directly from database.) The SQL syntax highly depends on the tables in your database. You may need to modify it a little bit for your own application.

### Drug-repo-App

> It is an prototype application written using CodeIgnite PHP framework
>
> I used a library called Aauth for the user management in this application. Just finish part of this part, for example creating a new user. Details can be found online _https://github.com/emreakay/CodeIgniter-Aauth_ and _https://github.com/emreakay/CodeIgniter-Aauth/wiki/_pages_
>
> This application is expandable so that put other functions, not just limited to heatmap drawing, in the framework
>
> The database connection setting is easy to handle when using this framework. Just need to change the database setting in application/config/database.php.
