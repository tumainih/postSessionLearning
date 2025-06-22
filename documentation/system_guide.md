# Post Session Learning System - Complete System Guide

## 📋 Table of Contents
1. [System Overview](#system-overview)
2. [Database Structure](#database-structure)
3. [File Structure & Components](#file-structure--components)
4. [User Roles & Access Control](#user-roles--access-control)
5. [Core Features](#core-features)
6. [How Each Feature Works](#how-each-feature-works)
7. [Navigation & User Interface](#navigation--user-interface)
8. [Admin Functions](#admin-functions)
9. [Student Functions](#student-functions)
10. [Technical Implementation](#technical-implementation)
11. [Troubleshooting](#troubleshooting)

---

## 🎯 System Overview

The Post Session Learning System is a Yii2-based web application designed for educational feedback collection. It allows students to provide feedback on concepts they understand or don't understand, with a hierarchical content structure.

### Key Features:
- **Hierarchical Content**: Courses → Modules → Lectures → Concepts
- **Feedback System**: Green (Understood) / Red (Not Understood) buttons
- **Role-based Access**: Admin and Student roles
- **Dynamic Feedback**: Users can change from "Not Understood" to "Understood"
- **Reporting**: PDF reports and visual dashboards
- **User Management**: Registration, login, and profile management

---

## 🗄️ Database Structure

### Core Tables:

#### 1. **user** Table
- **Location**: `migrations/m250620_220710_create_user_table.php`
- **Purpose**: Stores user accounts and authentication data
- **Key Fields**:
  - `id` (Primary Key)
  - `username` (Login username)
  - `email` (User email)
  - `password_hash` (Encrypted password)
  - `role` (admin/student)
  - `contact_number` (Phone number)
  - `created_at` (Account creation date)

#### 2. **feedback** Table
- **Location**: `migrations/m250621_185823_add_user_id_to_feedback.php`
- **Purpose**: Stores student feedback on concepts
- **Key Fields**:
  - `id` (Primary Key)
  - `user_id` (Foreign key to user table)
  - `concept_id` (Foreign key to concept table)
  - `status` (understood/not_understood)
  - `created_at` (Feedback submission date)

#### 3. **concept** Table
- **Location**: `migrations/m250622_120000_create_hierarchical_structure.php`
- **Purpose**: Stores learning concepts
- **Key Fields**:
  - `id` (Primary Key)
  - `title` (Concept name)
  - `lecture_id` (Foreign key to lecture table)
  - `created_by` (Admin who created the concept)
  - `created_at` (Creation date)

#### 4. **lecture** Table
- **Location**: `migrations/m250622_120000_create_hierarchical_structure.php`
- **Purpose**: Organizes concepts into lectures
- **Key Fields**:
  - `id` (Primary Key)
  - `title` (Lecture name)
  - `module_id` (Foreign key to module table)
  - `created_at` (Creation date)

#### 5. **module** Table
- **Location**: `migrations/m250622_120000_create_hierarchical_structure.php`
- **Purpose**: Groups lectures by year/module
- **Key Fields**:
  - `id` (Primary Key)
  - `name` (Module name)
  - `year` (Academic year)
  - `course_id` (Foreign key to course table)
  - `created_at` (Creation date)

#### 6. **course** Table
- **Location**: `migrations/m250622_120000_create_hierarchical_structure.php`
- **Purpose**: Top-level organization of content
- **Key Fields**:
  - `id` (Primary Key)
  - `name` (Course name)
  - `description` (Course description)
  - `created_at` (Creation date)

---

## 📁 File Structure & Components

### Controllers (Business Logic)

#### 1. **ConceptController** - `controllers/ConceptController.php`
**Purpose**: Manages concept-related operations and feedback submission
**Key Actions**:
- `actionIndex()` - Shows all concepts with feedback buttons
- `actionSubmitFeedback()` - Handles feedback submission (Green/Red)
- `actionRemoveFeedback()` - Allows users to remove their feedback
- `actionDashboard()` - Admin dashboard with charts
- `actionViewFeedback()` - Shows lists of students who understood/not understood
- `actionMyFeedback()` - Shows user's personal feedback history
- `actionMyReport()` - Generates personal feedback reports
- `actionMyReportPdf()` - Creates PDF reports

#### 2. **CourseController** - `controllers/CourseController.php`
**Purpose**: Manages course creation and viewing
**Key Actions**:
- `actionIndex()` - Lists all courses
- `actionCreate()` - Creates new courses (Admin only)
- `actionView()` - Shows course details
- `actionStudentView()` - Student view of courses

#### 3. **ModuleController** - `controllers/ModuleController.php`
**Purpose**: Manages modules within courses
**Key Actions**:
- `actionIndex()` - Lists modules for a course
- `actionCreate()` - Creates new modules (Admin only)
- `actionStudentView()` - Student view of modules

#### 4. **LectureController** - `controllers/LectureController.php`
**Purpose**: Manages lectures within modules
**Key Actions**:
- `actionIndex()` - Lists lectures for a module
- `actionCreate()` - Creates new lectures (Admin only)
- `actionStudentView()` - Student view with concept feedback

#### 5. **SiteController** - `controllers/SiteController.php`
**Purpose**: Handles authentication and basic pages
**Key Actions**:
- `actionLogin()` - User login
- `actionRegister()` - User registration
- `actionLogout()` - User logout
- `actionIndex()` - Home page

#### 6. **UserController** - `controllers/UserController.php`
**Purpose**: Manages user accounts (Admin only)
**Key Actions**:
- `actionIndex()` - Lists all users

### Models (Data Layer)

#### 1. **Concept Model** - `models/Concept.php`
**Purpose**: Represents concepts in the database
**Key Relations**:
- `getFeedbacks()` - Gets all feedback for this concept
- `getCreatedBy()` - Gets the admin who created the concept
- `getLecture()` - Gets the parent lecture

#### 2. **Feedback Model** - `models/Feedback.php`
**Purpose**: Represents user feedback
**Key Relations**:
- `getConcept()` - Gets the concept this feedback is for
- `getUser()` - Gets the user who provided feedback
**Key Methods**:
- `isStatusUnderstood()` - Checks if status is "understood"
- `isStatusNotunderstood()` - Checks if status is "not_understood"

#### 3. **User Model** - `models/User.php`
**Purpose**: Represents user accounts
**Key Methods**:
- `isAdmin()` - Checks if user has admin role
- `validatePassword()` - Validates login password

#### 4. **Course, Module, Lecture Models**
**Purpose**: Represent the hierarchical structure
**Key Relations**: Each has relations to parent and child elements

### Views (User Interface)

#### 1. **Concept Views** - `views/concept/`
- `index.php` - Main concept list with feedback buttons
- `dashboard.php` - Admin dashboard with charts
- `create.php` - Form to create new concepts
- `view-feedback.php` - Lists students who understood/not understood
- `my-feedback.php` - User's personal feedback history
- `my-report.php` - Personal feedback report
- `my-report-pdf.php` - PDF report template

#### 2. **Course Views** - `views/course/`
- `index.php` - Lists all courses
- `create.php` - Form to create new courses
- `view.php` - Course details
- `student-view.php` - Student view of courses

#### 3. **Module Views** - `views/module/`
- `index.php` - Lists modules for a course
- `create.php` - Form to create new modules
- `student-view.php` - Student view of modules

#### 4. **Lecture Views** - `views/lecture/`
- `index.php` - Lists lectures for a module
- `create.php` - Form to create new lectures
- `student-view.php` - Student view with concept feedback

#### 5. **Site Views** - `views/site/`
- `login.php` - Login form
- `register.php` - Registration form
- `index.php` - Home page

### Configuration Files

#### 1. **Database Config** - `config/db.php`
**Purpose**: Database connection settings
**Key Settings**:
- Database host, name, username, password
- Connection charset and collation

#### 2. **Web Config** - `config/web.php`
**Purpose**: Main application configuration
**Key Settings**:
- URL rules and routing
- Component configurations
- Security settings

#### 3. **Console Config** - `config/console.php`
**Purpose**: Console application configuration
**Key Settings**:
- Database migrations
- Command configurations

---

## 👥 User Roles & Access Control

### Admin Role
**Capabilities**:
- Create, edit, and delete courses, modules, lectures, and concepts
- View all student feedback
- Access admin dashboard with charts
- Manage user accounts
- View detailed feedback lists
- Generate system-wide reports

**Access Points**:
- Dashboard: `/concept/dashboard`
- User Management: `/user/index`
- Content Creation: All create/update forms

### Student Role
**Capabilities**:
- View courses, modules, lectures, and concepts
- Provide feedback (Green/Red buttons)
- Change feedback from "Not Understood" to "Understood"
- Remove their own feedback
- View personal feedback history
- Generate personal reports

**Access Points**:
- Concepts: `/concept/index`
- Personal Feedback: `/concept/my-feedback`
- Personal Reports: `/concept/my-report`

### Access Control Implementation
**Location**: `controllers/ConceptController.php` (lines 18-35)
```php
'access' => [
    'class' => AccessControl::class,
    'only' => [
        'index', 'create', 'dashboard', 'submit-feedback', 'remove-feedback',
        'view-feedback', 'my-feedback', 'my-report', 'download-report'
    ],
    'rules' => [
        [
            'allow' => true,
            'roles' => ['@'], // Authenticated users only
        ],
    ],
],
```

---

## ⭐ Core Features

### 1. Feedback System
**Purpose**: Allows students to indicate understanding of concepts
**Implementation**:
- **Green Button (Understood)**: `status = 'understood'`
- **Red Button (Not Understood)**: `status = 'not_understood'`
- **One-way Change**: Can change from Red → Green, but not Green → Red
- **Remove Option**: Users can completely remove their feedback

**Key Files**:
- Controller: `controllers/ConceptController.php` (actionSubmitFeedback)
- View: `views/concept/index.php` (feedback buttons)
- Model: `models/Feedback.php` (status constants)

### 2. Hierarchical Content Structure
**Purpose**: Organizes learning content in a logical hierarchy
**Structure**:
```
Course
├── Module (Year)
│   ├── Lecture
│   │   └── Concept
│   └── Lecture
│       └── Concept
└── Module (Year)
    └── Lecture
        └── Concept
```

**Key Files**:
- Models: `models/Course.php`, `models/Module.php`, `models/Lecture.php`, `models/Concept.php`
- Controllers: `controllers/CourseController.php`, `controllers/ModuleController.php`, `controllers/LectureController.php`

### 3. Dynamic Feedback Changes
**Purpose**: Allows users to update their understanding
**Rules**:
- ✅ Can change from "Not Understood" → "Understood"
- ❌ Cannot change from "Understood" → "Not Understood"
- 🗑️ Can remove feedback entirely

**Implementation**: `controllers/ConceptController.php` (lines 85-95)

### 4. Reporting System
**Purpose**: Provides insights into learning progress
**Types**:
- **Admin Dashboard**: Visual charts and statistics
- **Personal Reports**: Individual student feedback history
- **PDF Reports**: Downloadable reports
- **Feedback Lists**: Detailed student lists

**Key Files**:
- Dashboard: `views/concept/dashboard.php`
- Personal Report: `views/concept/my-report.php`
- PDF Report: `views/concept/my-report-pdf.php`

---

## 🔄 How Each Feature Works

### 1. User Authentication Flow
```
1. User visits /site/login
2. Enters username/password
3. SiteController validates credentials
4. User model checks password hash
5. Session created with user role
6. Redirected to appropriate dashboard
```

**Key Files**:
- Controller: `controllers/SiteController.php`
- Model: `models/User.php`
- View: `views/site/login.php`

### 2. Feedback Submission Flow
```
1. User clicks Green/Red button on concept
2. ConceptController::actionSubmitFeedback() called
3. System checks for existing feedback
4. If exists: Updates status (with restrictions)
5. If new: Creates new feedback record
6. Flash message shown to user
7. Redirected back to concept list
```

**Key Files**:
- Controller: `controllers/ConceptController.php` (actionSubmitFeedback)
- Model: `models/Feedback.php`
- View: `views/concept/index.php`

### 3. Content Creation Flow (Admin)
```
1. Admin navigates to create page
2. Fills out form (Course/Module/Lecture/Concept)
3. Controller validates input
4. Model saves to database
5. Success message shown
6. Redirected to index page
```

**Key Files**:
- Controllers: `controllers/CourseController.php`, `controllers/ModuleController.php`, etc.
- Views: `views/course/create.php`, `views/module/create.php`, etc.

### 4. Report Generation Flow
```
1. User requests report
2. Controller queries database for user's feedback
3. Data passed to view
4. View renders report template
5. For PDF: TCPDF generates document
6. File downloaded or displayed
```

**Key Files**:
- Controller: `controllers/ConceptController.php` (actionMyReport, actionMyReportPdf)
- Views: `views/concept/my-report.php`, `views/concept/my-report-pdf.php`

---

## 🧭 Navigation & User Interface

### Main Navigation
**Location**: `views/layouts/main.php` (lines 25-58)
**Menu Items**:
- **Home**: `/site/index`
- **Concepts**: `/concept/index`
- **My Report**: `/concept/my-report`
- **Dashboard**: `/concept/dashboard` (Admin only)
- **Users**: `/user/index` (Admin only)
- **My Feedback**: `/concept/my-feedback`
- **Login/Logout**: Authentication links

### Student Navigation Flow
```
Home → Courses → Modules → Lectures → Concepts → Feedback
                ↓
            My Feedback → My Report → PDF Download
```

### Admin Navigation Flow
```
Home → Dashboard → Course Management → Module Management → Lecture Management → Concept Management
                ↓
            User Management → Feedback Lists → System Reports
```

### Breadcrumb Navigation
**Implementation**: Each view includes breadcrumb navigation
**Example**: `views/lecture/student-view.php` (lines 7-11)
```php
$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['concept/index']];
$this->params['breadcrumbs'][] = ['label' => $model->module->course->name, 'url' => ['course/student-view', 'id' => $model->module->course_id]];
$this->params['breadcrumbs'][] = ['label' => $model->module->name, 'url' => ['module/student-view', 'id' => $model->module_id]];
$this->params['breadcrumbs'][] = $this->title;
```

---

## 🔧 Admin Functions

### 1. Dashboard Management
**Location**: `views/concept/dashboard.php`
**Features**:
- Visual charts showing feedback statistics
- Table with concept-wise feedback counts
- Links to detailed feedback lists
- Real-time data from database

### 2. Content Management
**Course Creation**: `views/course/create.php`
- Form fields: Name, Description
- Validation and error handling
- Success/error messages

**Module Creation**: `views/module/create.php`
- Form fields: Name, Year, Course selection
- Dropdown for course selection
- Year validation

**Lecture Creation**: `views/lecture/create.php`
- Form fields: Title, Module selection
- Dropdown for module selection

**Concept Creation**: `views/concept/create.php`
- Form fields: Title, Lecture selection
- Dropdown for lecture selection

### 3. User Management
**Location**: `views/user/index.php`
**Features**:
- List all registered users
- View user details
- Role-based filtering

### 4. Feedback Analysis
**Location**: `views/concept/view-feedback.php`
**Features**:
- Lists students who understood concepts
- Lists students who didn't understand concepts
- Contact information for understood students
- Date and time of feedback

---

## 👨‍🎓 Student Functions

### 1. Feedback Submission
**Location**: `views/concept/index.php`
**Process**:
1. View concepts with current feedback status
2. Click Green/Red button
3. Confirmation dialog (if changing from Red to Green)
4. Success message
5. Visual update of button states

### 2. Feedback Management
**Change Feedback**: 
- Red → Green: Allowed with confirmation
- Green → Red: Not allowed
- Remove Feedback: Complete removal option

**Visual States**:
- **No Feedback**: Both buttons active
- **Red Feedback**: Red disabled, Green active with helper text
- **Green Feedback**: Both disabled with explanation

### 3. Personal Reports
**Location**: `views/concept/my-feedback.php`
**Features**:
- Complete feedback history
- Date and time of submissions
- Status indicators (Green/Red)
- Export to PDF option

### 4. Navigation
**Student View**: `views/lecture/student-view.php`
**Features**:
- Hierarchical navigation (Course → Module → Lecture → Concept)
- Current feedback status for each concept
- Change feedback options
- Remove feedback option

---

## ⚙️ Technical Implementation

### 1. Database Migrations
**Location**: `migrations/` directory
**Key Migrations**:
- `m250620_220710_create_user_table.php` - User accounts
- `m250621_185823_add_user_id_to_feedback.php` - User feedback relation
- `m250622_120000_create_hierarchical_structure.php` - Content hierarchy

### 2. Model Relations
**Concept Model** (`models/Concept.php`):
```php
public function getFeedbacks()
{
    return $this->hasMany(Feedback::class, ['concept_id' => 'id']);
}

public function getLecture()
{
    return $this->hasOne(Lecture::class, ['id' => 'lecture_id']);
}
```

**Feedback Model** (`models/Feedback.php`):
```php
public function getConcept()
{
    return $this->hasOne(Concept::class, ['id' => 'concept_id']);
}

public function getUser()
{
    return $this->hasOne(User::class, ['id' => 'user_id']);
}
```

### 3. Access Control
**Implementation**: Yii2 AccessControl behavior
**Location**: Each controller's `behaviors()` method
**Example**: `controllers/ConceptController.php` (lines 18-35)

### 4. Flash Messages
**Implementation**: Yii2 session flash messages
**Usage**: `Yii::$app->session->setFlash('success', 'Message here');`
**Display**: `views/layouts/main.php` (Alert widget)

### 5. PDF Generation
**Library**: TCPDF
**Implementation**: `controllers/ConceptController.php` (actionMyReportPdf)
**Template**: `views/concept/my-report-pdf.php`

### 6. Form Validation
**Implementation**: Yii2 model validation rules
**Location**: Each model's `rules()` method
**Example**: `models/Concept.php` (lines 20-30)

---

## 🔍 Troubleshooting

### Common Issues & Solutions

#### 1. Database Connection Issues
**Symptoms**: "Database connection failed" error
**Solution**: Check `config/db.php` settings
**Files to Check**:
- `config/db.php` - Database configuration
- `config/test_db.php` - Test database configuration

#### 2. Migration Errors
**Symptoms**: "Migration failed" error
**Solution**: Run migrations in order
**Commands**:
```bash
php yii migrate/up
php yii migrate/down
```

#### 3. Permission Issues
**Symptoms**: "Access denied" errors
**Solution**: Check user roles and access control
**Files to Check**:
- `models/User.php` - Role checking methods
- Controller `behaviors()` methods - Access control rules

#### 4. Feedback Not Saving
**Symptoms**: Feedback buttons not working
**Solution**: Check database relations and foreign keys
**Files to Check**:
- `models/Feedback.php` - Relations and validation
- `controllers/ConceptController.php` - Feedback submission logic

#### 5. PDF Generation Issues
**Symptoms**: PDF not downloading or generating
**Solution**: Check TCPDF installation and permissions
**Files to Check**:
- `composer.json` - TCPDF dependency
- `controllers/ConceptController.php` - PDF generation code

### Debug Tools
**Yii2 Debug Toolbar**: Available in development mode
**Location**: Bottom of pages in development
**Features**:
- Database queries
- Request/response information
- Performance metrics
- Variable inspection

### Log Files
**Location**: `runtime/logs/`
**Types**:
- Application logs
- Error logs
- Access logs

---

## 📚 Additional Resources

### Configuration Files
- `composer.json` - PHP dependencies
- `requirements.php` - System requirements
- `codeception.yml` - Testing configuration

### Assets
- `assets/AppAsset.php` - CSS/JS asset management
- `web/css/site.css` - Custom styles
- `web/assets/` - Compiled assets

### Testing
- `tests/` - Test files
- `tests/acceptance/` - Acceptance tests
- `tests/functional/` - Functional tests
- `tests/unit/` - Unit tests

### Documentation
- `README.md` - Project overview
- `LICENSE.md` - License information

---

## 🎯 Quick Reference

### Key URLs
- **Home**: `/`
- **Login**: `/site/login`
- **Concepts**: `/concept/index`
- **Dashboard**: `/concept/dashboard`
- **My Feedback**: `/concept/my-feedback`
- **My Report**: `/concept/my-report`

### Key Commands
- **Start Server**: `php yii serve`
- **Run Migrations**: `php yii migrate/up`
- **Create User**: `php yii user/create`
- **Clear Cache**: `php yii cache/flush`

### Key Files for Modifications
- **Add New Feature**: Create controller action and view
- **Change UI**: Modify view files in `views/` directory
- **Change Logic**: Modify controller files in `controllers/` directory
- **Change Data**: Modify model files in `models/` directory
- **Change Database**: Create new migration

---

*This documentation covers the complete Post Session Learning System. For additional support or questions, refer to the Yii2 framework documentation or contact the development team.* 