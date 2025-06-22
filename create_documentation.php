<?php

// Include Yii2 framework
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Load configuration
$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

// Create PDF using TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Post Session Learning System');
$pdf->SetAuthor('System Documentation');
$pdf->SetTitle('Post Session Learning System - Complete Guide');
$pdf->SetSubject('System Documentation and User Guide');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 15, 15);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Create the content
$html = '
<style>
h1 { color: #2c3e50; font-size: 24px; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px; margin-bottom: 20px; }
h2 { color: #34495e; font-size: 18px; background-color: #ecf0f1; padding: 8px; border-left: 4px solid #3498db; margin-top: 20px; }
h3 { color: #2c3e50; font-size: 14px; border-bottom: 2px solid #3498db; padding-bottom: 5px; margin-top: 15px; }
p { margin: 8px 0; line-height: 1.5; }
ul { margin: 10px 0; padding-left: 20px; }
li { margin: 5px 0; }
code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
pre { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px; font-family: monospace; margin: 10px 0; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; font-weight: bold; }
</style>

<h1>📚 Post Session Learning System - Complete System Guide</h1>

<h2>🎯 System Overview</h2>
<p>The Post Session Learning System is a Yii2-based web application designed for educational feedback collection. It allows students to provide feedback on concepts they understand or don\'t understand, with a hierarchical content structure.</p>

<h3>Key Features:</h3>
<ul>
<li><strong>Hierarchical Content</strong>: Courses → Modules → Lectures → Concepts</li>
<li><strong>Feedback System</strong>: Green (Understood) / Red (Not Understood) buttons</li>
<li><strong>Role-based Access</strong>: Admin and Student roles</li>
<li><strong>Dynamic Feedback</strong>: Users can change from "Not Understood" to "Understood"</li>
<li><strong>Reporting</strong>: PDF reports and visual dashboards</li>
<li><strong>User Management</strong>: Registration, login, and profile management</li>
</ul>

<h2>🗄️ Database Structure</h2>

<h3>Core Tables:</h3>

<h4>1. user Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250620_220710_create_user_table.php</code></li>
<li><strong>Purpose</strong>: Stores user accounts and authentication data</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>username</code> (Login username)</li>
<li><code>email</code> (User email)</li>
<li><code>password_hash</code> (Encrypted password)</li>
<li><code>role</code> (admin/student)</li>
<li><code>contact_number</code> (Phone number)</li>
<li><code>created_at</code> (Account creation date)</li>
</ul>
</li>
</ul>

<h4>2. feedback Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250621_185823_add_user_id_to_feedback.php</code></li>
<li><strong>Purpose</strong>: Stores student feedback on concepts</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>user_id</code> (Foreign key to user table)</li>
<li><code>concept_id</code> (Foreign key to concept table)</li>
<li><code>status</code> (understood/not_understood)</li>
<li><code>created_at</code> (Feedback submission date)</li>
</ul>
</li>
</ul>

<h4>3. concept Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250622_120000_create_hierarchical_structure.php</code></li>
<li><strong>Purpose</strong>: Stores learning concepts</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>title</code> (Concept name)</li>
<li><code>lecture_id</code> (Foreign key to lecture table)</li>
<li><code>created_by</code> (Admin who created the concept)</li>
<li><code>created_at</code> (Creation date)</li>
</ul>
</li>
</ul>

<h4>4. lecture Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250622_120000_create_hierarchical_structure.php</code></li>
<li><strong>Purpose</strong>: Organizes concepts into lectures</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>title</code> (Lecture name)</li>
<li><code>module_id</code> (Foreign key to module table)</li>
<li><code>created_at</code> (Creation date)</li>
</ul>
</li>
</ul>

<h4>5. module Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250622_120000_create_hierarchical_structure.php</code></li>
<li><strong>Purpose</strong>: Groups lectures by year/module</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>name</code> (Module name)</li>
<li><code>year</code> (Academic year)</li>
<li><code>course_id</code> (Foreign key to course table)</li>
<li><code>created_at</code> (Creation date)</li>
</ul>
</li>
</ul>

<h4>6. course Table</h4>
<ul>
<li><strong>Location</strong>: <code>migrations/m250622_120000_create_hierarchical_structure.php</code></li>
<li><strong>Purpose</strong>: Top-level organization of content</li>
<li><strong>Key Fields</strong>:
<ul>
<li><code>id</code> (Primary Key)</li>
<li><code>name</code> (Course name)</li>
<li><code>description</code> (Course description)</li>
<li><code>created_at</code> (Creation date)</li>
</ul>
</li>
</ul>

<h2>📁 File Structure & Components</h2>

<h3>Controllers (Business Logic)</h3>

<h4>1. ConceptController - <code>controllers/ConceptController.php</code></h4>
<p><strong>Purpose</strong>: Manages concept-related operations and feedback submission</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionIndex()</code> - Shows all concepts with feedback buttons</li>
<li><code>actionSubmitFeedback()</code> - Handles feedback submission (Green/Red)</li>
<li><code>actionRemoveFeedback()</code> - Allows users to remove their feedback</li>
<li><code>actionDashboard()</code> - Admin dashboard with charts</li>
<li><code>actionViewFeedback()</code> - Shows lists of students who understood/not understood</li>
<li><code>actionMyFeedback()</code> - Shows user\'s personal feedback history</li>
<li><code>actionMyReport()</code> - Generates personal feedback reports</li>
<li><code>actionMyReportPdf()</code> - Creates PDF reports</li>
</ul>

<h4>2. CourseController - <code>controllers/CourseController.php</code></h4>
<p><strong>Purpose</strong>: Manages course creation and viewing</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionIndex()</code> - Lists all courses</li>
<li><code>actionCreate()</code> - Creates new courses (Admin only)</li>
<li><code>actionView()</code> - Shows course details</li>
<li><code>actionStudentView()</code> - Student view of courses</li>
</ul>

<h4>3. ModuleController - <code>controllers/ModuleController.php</code></h4>
<p><strong>Purpose</strong>: Manages modules within courses</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionIndex()</code> - Lists modules for a course</li>
<li><code>actionCreate()</code> - Creates new modules (Admin only)</li>
<li><code>actionStudentView()</code> - Student view of modules</li>
</ul>

<h4>4. LectureController - <code>controllers/LectureController.php</code></h4>
<p><strong>Purpose</strong>: Manages lectures within modules</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionIndex()</code> - Lists lectures for a module</li>
<li><code>actionCreate()</code> - Creates new lectures (Admin only)</li>
<li><code>actionStudentView()</code> - Student view with concept feedback</li>
</ul>

<h4>5. SiteController - <code>controllers/SiteController.php</code></h4>
<p><strong>Purpose</strong>: Handles authentication and basic pages</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionLogin()</code> - User login</li>
<li><code>actionRegister()</code> - User registration</li>
<li><code>actionLogout()</code> - User logout</li>
<li><code>actionIndex()</code> - Home page</li>
</ul>

<h4>6. UserController - <code>controllers/UserController.php</code></h4>
<p><strong>Purpose</strong>: Manages user accounts (Admin only)</p>
<p><strong>Key Actions</strong>:</p>
<ul>
<li><code>actionIndex()</code> - Lists all users</li>
</ul>

<h3>Models (Data Layer)</h3>

<h4>1. Concept Model - <code>models/Concept.php</code></h4>
<p><strong>Purpose</strong>: Represents concepts in the database</p>
<p><strong>Key Relations</strong>:</p>
<ul>
<li><code>getFeedbacks()</code> - Gets all feedback for this concept</li>
<li><code>getCreatedBy()</code> - Gets the admin who created the concept</li>
<li><code>getLecture()</code> - Gets the parent lecture</li>
</ul>

<h4>2. Feedback Model - <code>models/Feedback.php</code></h4>
<p><strong>Purpose</strong>: Represents user feedback</p>
<p><strong>Key Relations</strong>:</p>
<ul>
<li><code>getConcept()</code> - Gets the concept this feedback is for</li>
<li><code>getUser()</code> - Gets the user who provided feedback</li>
</ul>
<p><strong>Key Methods</strong>:</p>
<ul>
<li><code>isStatusUnderstood()</code> - Checks if status is "understood"</li>
<li><code>isStatusNotunderstood()</code> - Checks if status is "not_understood"</li>
</ul>

<h4>3. User Model - <code>models/User.php</code></h4>
<p><strong>Purpose</strong>: Represents user accounts</p>
<p><strong>Key Methods</strong>:</p>
<ul>
<li><code>isAdmin()</code> - Checks if user has admin role</li>
<li><code>validatePassword()</code> - Validates login password</li>
</ul>

<h4>4. Course, Module, Lecture Models</h4>
<p><strong>Purpose</strong>: Represent the hierarchical structure</p>
<p><strong>Key Relations</strong>: Each has relations to parent and child elements</p>

<h3>Views (User Interface)</h3>

<h4>1. Concept Views - <code>views/concept/</code></h4>
<ul>
<li><code>index.php</code> - Main concept list with feedback buttons</li>
<li><code>dashboard.php</code> - Admin dashboard with charts</li>
<li><code>create.php</code> - Form to create new concepts</li>
<li><code>view-feedback.php</code> - Lists students who understood/not understood</li>
<li><code>my-feedback.php</code> - User\'s personal feedback history</li>
<li><code>my-report.php</code> - Personal feedback report</li>
<li><code>my-report-pdf.php</code> - PDF report template</li>
</ul>

<h4>2. Course Views - <code>views/course/</code></h4>
<ul>
<li><code>index.php</code> - Lists all courses</li>
<li><code>create.php</code> - Form to create new courses</li>
<li><code>view.php</code> - Course details</li>
<li><code>student-view.php</code> - Student view of courses</li>
</ul>

<h4>3. Module Views - <code>views/module/</code></h4>
<ul>
<li><code>index.php</code> - Lists modules for a course</li>
<li><code>create.php</code> - Form to create new modules</li>
<li><code>student-view.php</code> - Student view of modules</li>
</ul>

<h4>4. Lecture Views - <code>views/lecture/</code></h4>
<ul>
<li><code>index.php</code> - Lists lectures for a module</li>
<li><code>create.php</code> - Form to create new lectures</li>
<li><code>student-view.php</code> - Student view with concept feedback</li>
</ul>

<h4>5. Site Views - <code>views/site/</code></h4>
<ul>
<li><code>login.php</code> - Login form</li>
<li><code>register.php</code> - Registration form</li>
<li><code>index.php</code> - Home page</li>
</ul>

<h3>Configuration Files</h3>

<h4>1. Database Config - <code>config/db.php</code></h4>
<p><strong>Purpose</strong>: Database connection settings</p>
<p><strong>Key Settings</strong>:</p>
<ul>
<li>Database host, name, username, password</li>
<li>Connection charset and collation</li>
</ul>

<h4>2. Web Config - <code>config/web.php</code></h4>
<p><strong>Purpose</strong>: Main application configuration</p>
<p><strong>Key Settings</strong>:</p>
<ul>
<li>URL rules and routing</li>
<li>Component configurations</li>
<li>Security settings</li>
</ul>

<h4>3. Console Config - <code>config/console.php</code></h4>
<p><strong>Purpose</strong>: Console application configuration</p>
<p><strong>Key Settings</strong>:</p>
<ul>
<li>Database migrations</li>
<li>Command configurations</li>
</ul>

<h2>👥 User Roles & Access Control</h2>

<h3>Admin Role</h3>
<p><strong>Capabilities</strong>:</p>
<ul>
<li>Create, edit, and delete courses, modules, lectures, and concepts</li>
<li>View all student feedback</li>
<li>Access admin dashboard with charts</li>
<li>Manage user accounts</li>
<li>View detailed feedback lists</li>
<li>Generate system-wide reports</li>
</ul>

<p><strong>Access Points</strong>:</p>
<ul>
<li>Dashboard: <code>/concept/dashboard</code></li>
<li>User Management: <code>/user/index</code></li>
<li>Content Creation: All create/update forms</li>
</ul>

<h3>Student Role</h3>
<p><strong>Capabilities</strong>:</p>
<ul>
<li>View courses, modules, lectures, and concepts</li>
<li>Provide feedback (Green/Red buttons)</li>
<li>Change feedback from "Not Understood" to "Understood"</li>
<li>Remove their own feedback</li>
<li>View personal feedback history</li>
<li>Generate personal reports</li>
</ul>

<p><strong>Access Points</strong>:</p>
<ul>
<li>Concepts: <code>/concept/index</code></li>
<li>Personal Feedback: <code>/concept/my-feedback</code></li>
<li>Personal Reports: <code>/concept/my-report</code></li>
</ul>

<h3>Access Control Implementation</h3>
<p><strong>Location</strong>: <code>controllers/ConceptController.php</code> (lines 18-35)</p>
<pre>
\'access\' => [
    \'class\' => AccessControl::class,
    \'only\' => [
        \'index\', \'create\', \'dashboard\', \'submit-feedback\', \'remove-feedback\',
        \'view-feedback\', \'my-feedback\', \'my-report\', \'download-report\'
    ],
    \'rules\' => [
        [
            \'allow\' => true,
            \'roles\' => [\'@\'], // Authenticated users only
        ],
    ],
],
</pre>

<h2>⭐ Core Features</h2>

<h3>1. Feedback System</h3>
<p><strong>Purpose</strong>: Allows students to indicate understanding of concepts</p>
<p><strong>Implementation</strong>:</p>
<ul>
<li><strong>Green Button (Understood)</strong>: <code>status = \'understood\'</code></li>
<li><strong>Red Button (Not Understood)</strong>: <code>status = \'not_understood\'</code></li>
<li><strong>One-way Change</strong>: Can change from Red → Green, but not Green → Red</li>
<li><strong>Remove Option</strong>: Users can completely remove their feedback</li>
</ul>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Controller: <code>controllers/ConceptController.php</code> (actionSubmitFeedback)</li>
<li>View: <code>views/concept/index.php</code> (feedback buttons)</li>
<li>Model: <code>models/Feedback.php</code> (status constants)</li>
</ul>

<h3>2. Hierarchical Content Structure</h3>
<p><strong>Purpose</strong>: Organizes learning content in a logical hierarchy</p>
<p><strong>Structure</strong>:</p>
<pre>
Course
├── Module (Year)
│   ├── Lecture
│   │   └── Concept
│   └── Lecture
│       └── Concept
└── Module (Year)
    └── Lecture
        └── Concept
</pre>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Models: <code>models/Course.php</code>, <code>models/Module.php</code>, <code>models/Lecture.php</code>, <code>models/Concept.php</code></li>
<li>Controllers: <code>controllers/CourseController.php</code>, <code>controllers/ModuleController.php</code>, <code>controllers/LectureController.php</code></li>
</ul>

<h3>3. Dynamic Feedback Changes</h3>
<p><strong>Purpose</strong>: Allows users to update their understanding</p>
<p><strong>Rules</strong>:</p>
<ul>
<li>✅ Can change from "Not Understood" → "Understood"</li>
<li>❌ Cannot change from "Understood" → "Not Understood"</li>
<li>🗑️ Can remove feedback entirely</li>
</ul>

<p><strong>Implementation</strong>: <code>controllers/ConceptController.php</code> (lines 85-95)</p>

<h3>4. Reporting System</h3>
<p><strong>Purpose</strong>: Provides insights into learning progress</p>
<p><strong>Types</strong>:</p>
<ul>
<li><strong>Admin Dashboard</strong>: Visual charts and statistics</li>
<li><strong>Personal Reports</strong>: Individual student feedback history</li>
<li><strong>PDF Reports</strong>: Downloadable reports</li>
<li><strong>Feedback Lists</strong>: Detailed student lists</li>
</ul>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Dashboard: <code>views/concept/dashboard.php</code></li>
<li>Personal Report: <code>views/concept/my-report.php</code></li>
<li>PDF Report: <code>views/concept/my-report-pdf.php</code></li>
</ul>

<h2>🔄 How Each Feature Works</h2>

<h3>1. User Authentication Flow</h3>
<pre>
1. User visits /site/login
2. Enters username/password
3. SiteController validates credentials
4. User model checks password hash
5. Session created with user role
6. Redirected to appropriate dashboard
</pre>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Controller: <code>controllers/SiteController.php</code></li>
<li>Model: <code>models/User.php</code></li>
<li>View: <code>views/site/login.php</code></li>
</ul>

<h3>2. Feedback Submission Flow</h3>
<pre>
1. User clicks Green/Red button on concept
2. ConceptController::actionSubmitFeedback() called
3. System checks for existing feedback
4. If exists: Updates status (with restrictions)
5. If new: Creates new feedback record
6. Flash message shown to user
7. Redirected back to concept list
</pre>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Controller: <code>controllers/ConceptController.php</code> (actionSubmitFeedback)</li>
<li>Model: <code>models/Feedback.php</code></li>
<li>View: <code>views/concept/index.php</code></li>
</ul>

<h3>3. Content Creation Flow (Admin)</h3>
<pre>
1. Admin navigates to create page
2. Fills out form (Course/Module/Lecture/Concept)
3. Controller validates input
4. Model saves to database
5. Success message shown
6. Redirected to index page
</pre>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Controllers: <code>controllers/CourseController.php</code>, <code>controllers/ModuleController.php</code>, etc.</li>
<li>Views: <code>views/course/create.php</code>, <code>views/module/create.php</code>, etc.</li>
</ul>

<h3>4. Report Generation Flow</h3>
<pre>
1. User requests report
2. Controller queries database for user\'s feedback
3. Data passed to view
4. View renders report template
5. For PDF: TCPDF generates document
6. File downloaded or displayed
</pre>

<p><strong>Key Files</strong>:</p>
<ul>
<li>Controller: <code>controllers/ConceptController.php</code> (actionMyReport, actionMyReportPdf)</li>
<li>Views: <code>views/concept/my-report.php</code>, <code>views/concept/my-report-pdf.php</code></li>
</ul>

<h2>🧭 Navigation & User Interface</h2>

<h3>Main Navigation</h3>
<p><strong>Location</strong>: <code>views/layouts/main.php</code> (lines 25-58)</p>
<p><strong>Menu Items</strong>:</p>
<ul>
<li><strong>Home</strong>: <code>/site/index</code></li>
<li><strong>Concepts</strong>: <code>/concept/index</code></li>
<li><strong>My Report</strong>: <code>/concept/my-report</code></li>
<li><strong>Dashboard</strong>: <code>/concept/dashboard</code> (Admin only)</li>
<li><strong>Users</strong>: <code>/user/index</code> (Admin only)</li>
<li><strong>My Feedback</strong>: <code>/concept/my-feedback</code></li>
<li><strong>Login/Logout</strong>: Authentication links</li>
</ul>

<h3>Student Navigation Flow</h3>
<pre>
Home → Courses → Modules → Lectures → Concepts → Feedback
                ↓
            My Feedback → My Report → PDF Download
</pre>

<h3>Admin Navigation Flow</h3>
<pre>
Home → Dashboard → Course Management → Module Management → Lecture Management → Concept Management
                ↓
            User Management → Feedback Lists → System Reports
</pre>

<h3>Breadcrumb Navigation</h3>
<p><strong>Implementation</strong>: Each view includes breadcrumb navigation</p>
<p><strong>Example</strong>: <code>views/lecture/student-view.php</code> (lines 7-11)</p>
<pre>
$this->params[\'breadcrumbs\'][] = [\'label\' => \'Courses\', \'url\' => [\'concept/index\']];
$this->params[\'breadcrumbs\'][] = [\'label\' => $model->module->course->name, \'url\' => [\'course/student-view\', \'id\' => $model->module->course_id]];
$this->params[\'breadcrumbs\'][] = [\'label\' => $model->module->name, \'url\' => [\'module/student-view\', \'id\' => $model->module_id]];
$this->params[\'breadcrumbs\'][] = $this->title;
</pre>

<h2>🔧 Admin Functions</h2>

<h3>1. Dashboard Management</h3>
<p><strong>Location</strong>: <code>views/concept/dashboard.php</code></p>
<p><strong>Features</strong>:</p>
<ul>
<li>Visual charts showing feedback statistics</li>
<li>Table with concept-wise feedback counts</li>
<li>Links to detailed feedback lists</li>
<li>Real-time data from database</li>
</ul>

<h3>2. Content Management</h3>
<p><strong>Course Creation</strong>: <code>views/course/create.php</code></p>
<ul>
<li>Form fields: Name, Description</li>
<li>Validation and error handling</li>
<li>Success/error messages</li>
</ul>

<p><strong>Module Creation</strong>: <code>views/module/create.php</code></p>
<ul>
<li>Form fields: Name, Year, Course selection</li>
<li>Dropdown for course selection</li>
<li>Year validation</li>
</ul>

<p><strong>Lecture Creation</strong>: <code>views/lecture/create.php</code></p>
<ul>
<li>Form fields: Title, Module selection</li>
<li>Dropdown for module selection</li>
</ul>

<p><strong>Concept Creation</strong>: <code>views/concept/create.php</code></p>
<ul>
<li>Form fields: Title, Lecture selection</li>
<li>Dropdown for lecture selection</li>
</ul>

<h3>3. User Management</h3>
<p><strong>Location</strong>: <code>views/user/index.php</code></p>
<p><strong>Features</strong>:</p>
<ul>
<li>List all registered users</li>
<li>View user details</li>
<li>Role-based filtering</li>
</ul>

<h3>4. Feedback Analysis</h3>
<p><strong>Location</strong>: <code>views/concept/view-feedback.php</code></p>
<p><strong>Features</strong>:</p>
<ul>
<li>Lists students who understood concepts</li>
<li>Lists students who didn\'t understand concepts</li>
<li>Contact information for understood students</li>
<li>Date and time of feedback</li>
</ul>

<h2>👨‍🎓 Student Functions</h2>

<h3>1. Feedback Submission</h3>
<p><strong>Location</strong>: <code>views/concept/index.php</code></p>
<p><strong>Process</strong>:</p>
<ol>
<li>View concepts with current feedback status</li>
<li>Click Green/Red button</li>
<li>Confirmation dialog (if changing from Red to Green)</li>
<li>Success message</li>
<li>Visual update of button states</li>
</ol>

<h3>2. Feedback Management</h3>
<p><strong>Change Feedback</strong>:</p>
<ul>
<li>Red → Green: Allowed with confirmation</li>
<li>Green → Red: Not allowed</li>
<li>Remove Feedback: Complete removal option</li>
</ul>

<p><strong>Visual States</strong>:</p>
<ul>
<li><strong>No Feedback</strong>: Both buttons active</li>
<li><strong>Red Feedback</strong>: Red disabled, Green active with helper text</li>
<li><strong>Green Feedback</strong>: Both disabled with explanation</li>
</ul>

<h3>3. Personal Reports</h3>
<p><strong>Location</strong>: <code>views/concept/my-feedback.php</code></p>
<p><strong>Features</strong>:</p>
<ul>
<li>Complete feedback history</li>
<li>Date and time of submissions</li>
<li>Status indicators (Green/Red)</li>
<li>Export to PDF option</li>
</ul>

<h3>4. Navigation</h3>
<p><strong>Student View</strong>: <code>views/lecture/student-view.php</code></p>
<p><strong>Features</strong>:</p>
<ul>
<li>Hierarchical navigation (Course → Module → Lecture → Concept)</li>
<li>Current feedback status for each concept</li>
<li>Change feedback options</li>
<li>Remove feedback option</li>
</ul>

<h2>⚙️ Technical Implementation</h2>

<h3>1. Database Migrations</h3>
<p><strong>Location</strong>: <code>migrations/</code> directory</p>
<p><strong>Key Migrations</strong>:</p>
<ul>
<li><code>m250620_220710_create_user_table.php</code> - User accounts</li>
<li><code>m250621_185823_add_user_id_to_feedback.php</code> - User feedback relation</li>
<li><code>m250622_120000_create_hierarchical_structure.php</code> - Content hierarchy</li>
</ul>

<h3>2. Model Relations</h3>
<p><strong>Concept Model</strong> (<code>models/Concept.php</code>):</p>
<pre>
public function getFeedbacks()
{
    return $this->hasMany(Feedback::class, [\'concept_id\' => \'id\']);
}

public function getLecture()
{
    return $this->hasOne(Lecture::class, [\'id\' => \'lecture_id\']);
}
</pre>

<p><strong>Feedback Model</strong> (<code>models/Feedback.php</code>):</p>
<pre>
public function getConcept()
{
    return $this->hasOne(Concept::class, [\'id\' => \'concept_id\']);
}

public function getUser()
{
    return $this->hasOne(User::class, [\'id\' => \'user_id\']);
}
</pre>

<h3>3. Access Control</h3>
<p><strong>Implementation</strong>: Yii2 AccessControl behavior</p>
<p><strong>Location</strong>: Each controller\'s <code>behaviors()</code> method</p>
<p><strong>Example</strong>: <code>controllers/ConceptController.php</code> (lines 18-35)</p>

<h3>4. Flash Messages</h3>
<p><strong>Implementation</strong>: Yii2 session flash messages</p>
<p><strong>Usage</strong>: <code>Yii::$app->session->setFlash(\'success\', \'Message here\');</code></p>
<p><strong>Display</strong>: <code>views/layouts/main.php</code> (Alert widget)</p>

<h3>5. PDF Generation</h3>
<p><strong>Library</strong>: TCPDF</p>
<p><strong>Implementation</strong>: <code>controllers/ConceptController.php</code> (actionMyReportPdf)</p>
<p><strong>Template</strong>: <code>views/concept/my-report-pdf.php</code></p>

<h3>6. Form Validation</h3>
<p><strong>Implementation</strong>: Yii2 model validation rules</p>
<p><strong>Location</strong>: Each model\'s <code>rules()</code> method</p>
<p><strong>Example</strong>: <code>models/Concept.php</code> (lines 20-30)</p>

<h2>🔍 Troubleshooting</h2>

<h3>Common Issues & Solutions</h3>

<h4>1. Database Connection Issues</h4>
<p><strong>Symptoms</strong>: "Database connection failed" error</p>
<p><strong>Solution</strong>: Check <code>config/db.php</code> settings</p>
<p><strong>Files to Check</strong>:</p>
<ul>
<li><code>config/db.php</code> - Database configuration</li>
<li><code>config/test_db.php</code> - Test database configuration</li>
</ul>

<h4>2. Migration Errors</h4>
<p><strong>Symptoms</strong>: "Migration failed" error</p>
<p><strong>Solution</strong>: Run migrations in order</p>
<p><strong>Commands</strong>:</p>
<pre>
php yii migrate/up
php yii migrate/down
</pre>

<h4>3. Permission Issues</h4>
<p><strong>Symptoms</strong>: "Access denied" errors</p>
<p><strong>Solution</strong>: Check user roles and access control</p>
<p><strong>Files to Check</strong>:</p>
<ul>
<li><code>models/User.php</code> - Role checking methods</li>
<li>Controller <code>behaviors()</code> methods - Access control rules</li>
</ul>

<h4>4. Feedback Not Saving</h4>
<p><strong>Symptoms</strong>: Feedback buttons not working</p>
<p><strong>Solution</strong>: Check database relations and foreign keys</p>
<p><strong>Files to Check</strong>:</p>
<ul>
<li><code>models/Feedback.php</code> - Relations and validation</li>
<li><code>controllers/ConceptController.php</code> - Feedback submission logic</li>
</ul>

<h4>5. PDF Generation Issues</h4>
<p><strong>Symptoms</strong>: PDF not downloading or generating</p>
<p><strong>Solution</strong>: Check TCPDF installation and permissions</p>
<p><strong>Files to Check</strong>:</p>
<ul>
<li><code>composer.json</code> - TCPDF dependency</li>
<li><code>controllers/ConceptController.php</code> - PDF generation code</li>
</ul>

<h3>Debug Tools</h3>
<p><strong>Yii2 Debug Toolbar</strong>: Available in development mode</p>
<p><strong>Location</strong>: Bottom of pages in development</p>
<p><strong>Features</strong>:</p>
<ul>
<li>Database queries</li>
<li>Request/response information</li>
<li>Performance metrics</li>
<li>Variable inspection</li>
</ul>

<h3>Log Files</h3>
<p><strong>Location</strong>: <code>runtime/logs/</code></p>
<p><strong>Types</strong>:</p>
<ul>
<li>Application logs</li>
<li>Error logs</li>
<li>Access logs</li>
</ul>

<h2>📚 Additional Resources</h2>

<h3>Configuration Files</h3>
<ul>
<li><code>composer.json</code> - PHP dependencies</li>
<li><code>requirements.php</code> - System requirements</li>
<li><code>codeception.yml</code> - Testing configuration</li>
</ul>

<h3>Assets</h3>
<ul>
<li><code>assets/AppAsset.php</code> - CSS/JS asset management</li>
<li><code>web/css/site.css</code> - Custom styles</li>
<li><code>web/assets/</code> - Compiled assets</li>
</ul>

<h3>Testing</h3>
<ul>
<li><code>tests/</code> - Test files</li>
<li><code>tests/acceptance/</code> - Acceptance tests</li>
<li><code>tests/functional/</code> - Functional tests</li>
<li><code>tests/unit/</code> - Unit tests</li>
</ul>

<h3>Documentation</h3>
<ul>
<li><code>README.md</code> - Project overview</li>
<li><code>LICENSE.md</code> - License information</li>
</ul>

<h2>🎯 Quick Reference</h2>

<h3>Key URLs</h3>
<ul>
<li><strong>Home</strong>: <code>/</code></li>
<li><strong>Login</strong>: <code>/site/login</code></li>
<li><strong>Concepts</strong>: <code>/concept/index</code></li>
<li><strong>Dashboard</strong>: <code>/concept/dashboard</code></li>
<li><strong>My Feedback</strong>: <code>/concept/my-feedback</code></li>
<li><strong>My Report</strong>: <code>/concept/my-report</code></li>
</ul>

<h3>Key Commands</h3>
<ul>
<li><strong>Start Server</strong>: <code>php yii serve</code></li>
<li><strong>Run Migrations</strong>: <code>php yii migrate/up</code></li>
<li><strong>Create User</strong>: <code>php yii user/create</code></li>
<li><strong>Clear Cache</strong>: <code>php yii cache/flush</code></li>
</ul>

<h3>Key Files for Modifications</h3>
<ul>
<li><strong>Add New Feature</strong>: Create controller action and view</li>
<li><strong>Change UI</strong>: Modify view files in <code>views/</code> directory</li>
<li><strong>Change Logic</strong>: Modify controller files in <code>controllers/</code> directory</li>
<li><strong>Change Data</strong>: Modify model files in <code>models/</code> directory</li>
<li><strong>Change Database</strong>: Create new migration</li>
</ul>

<h2>🎉 Summary</h2>

<p>This Post Session Learning System provides a comprehensive solution for educational feedback collection with the following key features:</p>

<ul>
<li><strong>Hierarchical Content Management</strong>: Organize content from courses down to individual concepts</li>
<li><strong>Dynamic Feedback System</strong>: Allow students to provide and update their understanding</li>
<li><strong>Role-based Access Control</strong>: Separate admin and student functionalities</li>
<li><strong>Comprehensive Reporting</strong>: Visual dashboards and downloadable reports</li>
<li><strong>User-friendly Interface</strong>: Intuitive navigation and clear visual feedback</li>
<li><strong>Robust Architecture</strong>: Built on Yii2 framework with proper MVC structure</li>
</ul>

<p>The system is designed to be scalable, maintainable, and user-friendly, providing both administrators and students with the tools they need for effective learning feedback management.</p>

<p><em>This documentation covers the complete Post Session Learning System. For additional support or questions, refer to the Yii2 framework documentation or contact the development team.</em></p>
';

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('Post_Session_Learning_System_Guide.pdf', 'D');
?> 