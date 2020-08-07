# BRWebApp

### This is the repository for the Bell Ripper's legal document automation software.

It runs on a LAMP stack that lives on a computer hosted by AWS.

- A LAMP stack is a combination of 4 technologies:

  - **Linux**   - The Operating System on which the following three technologies run on
  - **Apache2** - The Web Server. Responsible for displaying the actual website when users try to access the BR web portal URL
  -  **MySQL**   - The Database/Database management software. Responsible for storing and referencing all kinds of data, from users, to documents and surveys. 
  - **PHP**     - The Programming language used to orchestrate work done by Apache2 (webserver), MySql (database), and Linux (Operating System). PHP files also contains and dicates HTML code.

Apache2, MySql and PHP all run on Linux. All of these live on a computer hosted by Amazon (AWS).

The Project also contains a hefty amount of JavaScript.

JavaScript is a programming language that runs on web browsers like Chrome, Safari, and Edge. The main advantage of JavaScript is that it runs, calculates, and processes data in the users own web browser.
This saves resources on the system; instead of our AWS computer doing all these complex calculations, we let the clients Web Browser do them. 
