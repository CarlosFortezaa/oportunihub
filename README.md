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
