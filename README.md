# Fitness Tracker ([try it here](http://peterdrevicky.php5.cz/Fitness-Tracker/))
This website combines a fitness tracker functionality with a social network. Users can add workouts, monitor their progress by looking at their training history and table of best performances for individual exercises. Users also keep a list of friends with whom they can share instant messages. 

- It is written in vanilla JS and PHP.
- MySQL database is used to store user data.

## Login page  
  - User can login or click on the register button to show a register form
  - If login data are correct, the user is logged in otherwise notified about incorrect data
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/register_php.png)
  
## Register page
  - A new user can register on this page
  - After register button is pressed, the input data are evaluated and if there is a problem the user is notified
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/register_php_success.png)
  
  - If there is any problem, errors are show for each input field individually
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/register_php_errors.png)
  
## Profile page
  This is the user's home page and contains several features:
  - User's profile picture - user can see his profile picture and upload a new one
  - Information about the user (age, contact email etc.) which he/she can modify
  - A list of user's friends. The user can on one of them to see their profile 
  or click on the message icon and view past messages and send new ones.
  - New friends can be added by using the search bar below (names are suggested automatically based on current input - ajax is used to retrieve suggestions from the server)
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/profile.png)
  
## Messages page
  - Displays chat with the selected friend. I wanted the server to notify the user's browser when the user receives a new message but unfortunately the hosting I was using did not allow me to install new libraries on the server side. New messages are received from server by polling it using a small time interval.
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/messages.png)
  
## Friend profile page
  - Shows the selected friend's profile page which includes their picture, profile information and a list of their friends
  
 ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/friend_profile.png)
 
## Add new training session page
  - User can add information about the training session he did. He can also record his thoughts about it in a note
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/add_new_training.png)
  
## Training history page
  - Shows user his training history with all the training sessions ordered from newest to oldest
  
  ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/training_history.png)
  
## Best performances page
 - Show user his best performances for each exercise and the date of the training session on which they happened
 
 ![](https://github.com/pdrevicky/Fitness-Tracker/blob/master/images/best_performances.png)
