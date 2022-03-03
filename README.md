# CakePHP Application. Workflow Manager

A task management and workflow management application created for an small melbourne accounting firm
Created as a part of FIT3048 at Monash University

## Explanation

#Users

Users have the ability to view all their tasks on a week by week basis
They can view other employees tasks in a trello style board and filter by employee

Authentication used, Users with the level Manager and up can edit lower level employees profiles.
Managers can add clients

#Tasks

Tasks could be created with basic information. Sub-tasks could then be linked to them and the tasks could be set to recur on a specified basis. (or infinitely)
Tasks became overdue when the due date was passed.
Tasks are displayed through a modal, that could be edited from the modal.
Tasks could be assigned to a specific employee and linked to a client 

#Clients

Users can view clients information and see a board of tasks displayed for the client
Can add related tasks, directly from client screen

KPIs were developed and displayed based on user authentication level. Relating to task completion % and late completion %. Allowed managers to track employee performance

## Software Explanation
CakePHP was used as the backend framework for this application
A lot of the application was built with JQuery and PHP
MySQL was used for the database

![image](https://user-images.githubusercontent.com/99526472/156484361-5a4435f0-91cf-485f-b4ff-e00f2d4c21e9.png)
![image](https://user-images.githubusercontent.com/99526472/156485823-12869122-0f95-41ea-bb5d-1e21e6e79d35.png)
![image](https://user-images.githubusercontent.com/99526472/156486017-f7fd3786-18b7-4448-916a-00cc9cabf297.png)
![image](https://user-images.githubusercontent.com/99526472/156486042-6d055a00-e51d-4082-ab72-80fcd9934c2a.png)
![image](https://user-images.githubusercontent.com/99526472/156486069-ca6c7878-7196-4835-91f8-7e89f3b72f19.png)
