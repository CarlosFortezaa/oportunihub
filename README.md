# OportuniHub

OportuniHub is a full-stack PHP + MySQL web application for posting and managing opportunities such as jobs, internships, and research positions. It includes authentication, role-based access control, CRUD functionality, subscription-based email notifications, and session management.

Developed as a team project by Carlos Forteza, Alexander Martinez, Mario Rios, and Rosana Berrios, focusing on collaborative development and full-stack implementation practices.

---

## Features

### Authentication & Access Control
- Secure login and logout system
- Role-based access control (admin, contributor, visitor)
- Only admins can create new user accounts

### Opportunity Management
- Create, edit, and delete opportunities
- Each opportunity can include optional:
  - External URL
  - File attachment
- View opportunity listings

### Search
- Search bar to filter opportunities by keywords

### Email Subscription System
- Users can subscribe to receive email notifications
- Emails are automatically sent when new opportunities are created

### Backend & Data Management
- PHP server-side logic
- MySQL database integration
- Session-based authentication and user tracking

---

## Opportunity Structure

Each opportunity includes the following information:

- **Title** – Name of the opportunity  
- **Description** – Detailed information  
- **Sponsor** – Organization or company providing it  
- **External Link** – Optional URL reference  
- **File Attachment** – Optional uploaded document  
- **Publication Date** – Automatically generated when created  
- **Deadline** – Selected by the creator  
- **Type** – Category (job, internship, research, etc.)  
- **Posted By** – Automatically assigned based on the logged-in user session  

---

## Tech Stack

- PHP
- MySQL
- HTML
- CSS

## Screenshots
### Login Page
  Authentication system where users sign in with their account credentials.
<img width="2492" height="1267" alt="login page screenshot" src="https://github.com/user-attachments/assets/c264703e-a8b5-4591-8bdd-473601a4d903" />


### Admin - Create User Page
  Page where admins can create new user accounts and assign roles.
<img width="2503" height="1262" alt="create user screenshot" src="https://github.com/user-attachments/assets/01c74a30-8eda-417b-be72-1ae042a4d4b4" />


### Admin - Manage Users Page
  Page where admins can view, edit, or delete existing user accounts
<img width="2503" height="1267" alt="administrate users screenshot" src="https://github.com/user-attachments/assets/c0a79366-2bd0-4003-adef-a3caa995508d" />


### Opportunities List
  Main view of the system displaying all available opportunities. Includes search functionality.

(Visitor View)
<img width="2502" height="1273" alt="opportunities list from visitor view screenshot" src="https://github.com/user-attachments/assets/9cd45772-d4ee-4ff8-b919-297d28d8b236" />

(Contributor View)
<img width="2492" height="1274" alt="opportunities list from contributor view screenshot" src="https://github.com/user-attachments/assets/116b96dd-3645-4736-a626-b7f3de6c389b" />

(Admin View)
<img width="2496" height="1269" alt="opportunities list from adminstrator view screenshot" src="https://github.com/user-attachments/assets/d1d89f34-7cbb-44ab-8b20-b239dfb8aafd" />


### Create Opportunity
  Form used by authorized users to create new opportunities.
<img width="2511" height="1263" alt="create opportunity form screenshot" src="https://github.com/user-attachments/assets/aab3fea2-de46-4a48-8145-73f86d547250" />


### Email List
Form used to manage email subscriptions for users who want to receive notifications when new opportunities are posted.
<img width="2500" height="1278" alt="image" src="https://github.com/user-attachments/assets/703b5e72-28f3-4604-a50b-297cdb0e2ae3" />


## Profile View
  Main view of the user profile where users can update their email and password.
<img width="2499" height="1270" alt="image" src="https://github.com/user-attachments/assets/6cc08ae5-d985-410c-8db6-680916858b33" />



