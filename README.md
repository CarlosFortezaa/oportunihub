# Oportunihub
OportuniHub is a full-stack PHP + MySQL web application for posting and managing opportunities such as jobs, internships, and research positions. It includes authentication, role-based access control (admin, contributor, visitor), CRUD functionality, subscription features, and session-based user management backed by a relational database.
The project was developed as a team effort by Carlos Forteza, Alexander Martinez, Mario Rios, and Rosana Berrios, focusing on collaborative development, shared responsibilities, and full-stack implementation practices.

## Features

### Authentication & Access Control
- Secure login and logout system
- Role-based access control:
  - Admin
  - Contributor
  - Visitor
- Only administrators can create new user accounts

### Opportunity Management
- Admins and authorized users can create, edit, and delete opportunities
- View list of available opportunities (jobs, internships, research)

### Email Subscription System
- Users can subscribe to receive email notifications
- Automatic email alerts are sent when a new opportunity is created

### Backend & Data Management
- PHP-based server-side logic
- MySQL database integration
- Session handling for user authentication and persistence
