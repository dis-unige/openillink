# OpenILLink Install
# This version is working in Lausanne and Geneva medical libraries

Source code :
https://github.com/pablogit/openerm/tree/master/openillink
 

 
You can install the system easily from github, it works fine on a server with PHP >= 5.2, here you are the main install steps :
 
1. Create a MySQL database and import the file with the test data "openillink.sql" that you will find on "data" folder. This import make at the same time the structure and the different tables of the database.
 
2. Edit the file "includes/config.php" and change the access codes to your MySQL database
 
3. Modify all the personal variables on "config.php" with your values (name of the library, address, e-mail, security codes, IP addresses, etc. If you don't want to use some parameters (second IP range for example) you must declare those variables with an empty value (="")
 
4. Modify the translations that you will find on "includes/translations.php". At the moment, only the main page is multilingual, for the other pages it's on the to-do list but you can translate directly the code
 
5. Modify the text of the preconfigured e-mails on "includes/email.php"
 
6. Copy all files and folders on your server without the "data" folder (needed only to build the database at the step 1)
 
Then you can try to connect to the admin panel with this codes :
 
Superadmin
Login : sadmin
Pwd : sadmin

 
Administrator
Login : admin
Pwd : admin
 
User (staff collab.)
Login : user
Pwd : user
 
Those codes allow you to test the interface, normally you will find 20 orders preconfigured. Then, if all works fine you must change the login/pwd to avoid security issues.
 
Talking about rights, only the superadmin can create admins and so on. The "user" login allows you to work with orders and modify them but not to delete them. Administrators has plenty rights and they can add/modify users but they can't add/modify other administrators or superadministratos. The administrators can "see" only the orders of their own library. Superadmins could "see" all the orders of the database.
 
Administrators and superadministrators have access to the 6 linked tables :
 
1.        Libraries (Bibliothèques) : The different libraries of your network. The system can manage a library network where each library "see" only their orders and could "send" orders to others, but you can use only one lilbrary if you want, I don't know if your library has some satellites or not.
2.        Places (Localisations) : The different localizations for the documents of each library
3.        Users : The users of the back office, you don't need to manage users for the end users, only for ILL professionals of the library
4.        Units / Groups (Unités / Services) : The different units listed on the menu of the order form. They are linked to the libraries defined below and to the IP ranges so you can choose to display some units only for the users of your network and others for the external users. If you check the field "A valider" then the orders of the collaborators of this unit enter the system with the status "to be validate" rather than "new order"
5.        Order steps (status) : The different status of the orders that you want to differentiate. There are no limits but you will find 5 "special status" with the important values that I recommend to not be removed or reused. Those status are important because they are used by other pieces of the system. Each status determines the folder where the orders could be found : IN (new orders addressed to my library by users or the others libraries), OUT (orders pushed to suppliers or to the others libraries) and TRASH (to be deleted). Two exceptions : the "rejected" orders are showed always on the IN folder of the both libraries requester and responder, and the status "to be renewed" disappears from the IN folder and appears only when the renewal date comes.
6.        Links : The different links displayed on the details of the order. They are used to search external databases and to push the order into the external document delivery systems. I've included all the links that we use now at our library, but sometimes you must modify the link with your own codes on the places marked by "[]" (codes for Subito for example). You can create your own links easily using this codes (near to the OpenURL 0.1) that will be replaced contextually with the values of the order displayed :

    doi : XDOIX
    pmid (PubMed identifier) : XPMIDX
    genre (Document Type) : XGENREX
    aulast (Authors names) : XAULASTX
    issn : XISSNX
    eissn : XEISSNX
    isbn : XISBNX
    title (Journal name) : XTITLEX
    atitle (Article/chapter title) : XATITLEX
    volume : XVOLUMEX
    issue : XISSUEX
    pages : XPAGESX
    date : XDATEX
    end user name : XNAMEX

For example, you can search the database that you want to include with those codes as search criteria, "XISSNX" on the field ISSN and so on, then you can copy the l'URL generated by the database and paste into your link. Sometimes the external sites use POST forms and they don't allow the GET equivalent. For those cases we have to recreate the whole form to imitate the POST request. You will find some forms like that on the "forms" folder, with the examples of the forms used by the NLM or at Basel university library. You can see those forms and copy theme in order to make new ones. Then you can save your form on this folder and create a new link with the name of the target and check the field "formulaire interne" (internal form). 