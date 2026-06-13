# OportuniHub

OportuniHub is a full-stack PHP + MySQL web application for posting and managing opportunities such as jobs, internships, and research positions. It includes authentication, role-based access control, CRUD functionality, subscription-based email notifications, and session management.

Developed as a team project by Carlos Forteza, Alexander Martinez, Mario Rios, and Rosana Berrios, focusing on collaborative development and full-stack implementation practices.

---

## Features

### Authentication & Access Control
- Secure login and logout system
- Role-based access control (admin, contributor, visitor)
- Only administrators can create new user accounts

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
  Authentication system where users sign with their accounts
<img width="2492" height="1267" alt="image" src="https://github.com/user-attachments/assets/c264703e-a8b5-4591-8bdd-473601a4d903" />

### Admin Dashboard
  Main pages where the admin can manage users and create accounts for new users
<img width="2503" height="1262" alt="image" src="https://github.com/user-attachments/assets/01c74a30-8eda-417b-be72-1ae042a4d4b4" />
<img width="2503" height="1267" alt="image" src="https://github.com/user-attachments/assets/c0a79366-2bd0-4003-adef-a3caa995508d" />

### Opportunities List
  Main view of the system displaying all available opportunities. Includes search functionality
<img width="2502" height="1273" alt="image" src="https://github.com/user-attachments/assets/9cd45772-d4ee-4ff8-b919-297d28d8b236" />(visitor view)
<img width="2492" height="1274" alt="image" src="https://github.com/user-attachments/assets/116b96dd-3645-4736-a626-b7f3de6c389b" />(contrubutor view)
<img width="2496" height="1269" alt="image" src="https://github.com/user-attachments/assets/d1d89f34-7cbb-44ab-8b20-b239dfb8aafd" />(admin view)

### Create Opportunity
  Form used by authorized users to create new opportunitites.
<img width="2511" height="1263" alt="image" src="https://github.com/user-attachments/assets/aab3fea2-de46-4a48-8145-73f86d547250" />




