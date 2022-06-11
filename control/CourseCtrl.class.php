<?php
/**
 * Course Controller Class
 * @author mzijlstra 2021-10-07
 *
 * @Controller
 */
class CourseCtrl {
   	/**
	 * @Inject("CourseDao")
	 */
	public $courseDao;
   	/**
	 * @Inject("OfferingDao")
	 */
	public $offeringDao;
    /**
     * @Inject("VideoDao")
     */
    public $videoDao;
    /**
     * @Inject("DayDao")
     */
    public $dayDao;
    /**
     * @Inject('EnrollmentDao')
     */
    public $enrollmentDao;
    /**
     * @Inject('UserDao')
     */
    public $userDao;
    /**
     * @Inject('SessionDao')
     */
    public $sessionDao;

    /**
     * @GET(uri="!^/?$!", sec="user")
     */
    public function showCourses() {
        global $VIEW_DATA;

        $offerings = $this->offeringDao->all();

        $VIEW_DATA["title"] = "Course Offerings";
        $VIEW_DATA["offerings"] = $offerings;
        return "courses.php";
    }

    /**
     * @POST(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/clone$!", sec="admin")
     */
    public function cloneOffering() {
        global $URI_PARAMS;

        $course_number = $URI_PARAMS[1];
        $old_block = $URI_PARAMS[2];

		$offering_id = filter_input(INPUT_POST, "offering_id", FILTER_SANITIZE_NUMBER_INT);
		$fac_user_id = filter_input(INPUT_POST, "fac_user_id", FILTER_SANITIZE_NUMBER_INT);
        $block = filter_input(INPUT_POST, "block", FILTER_SANITIZE_STRING);
        $start = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);

        // calculate stop date
        $stop = date_create($start);
        date_add($stop, date_interval_create_from_date_string("24 days"));
        $stop = date_format($stop, "Y-m-d");

        $this->videoDao->clone($course_number, $block, $old_block);
        $new_offering = $this->offeringDao->create($course_number, $block, 
                                            $start, $stop, $fac_user_id);
        $this->dayDao->cloneDays($offering_id, $new_offering);
        $this->sessionDao->createForOffering($new_offering);

        return "Location: ../$block/";
    }

    /**
     * @POST(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/edit$!", sec="admin")
     */
    public function editDay() {
        global $URI_PARAMS;
        $block = $URI_PARAMS[2];

        $day_id = filter_input(INPUT_POST, "day_id", FILTER_SANITIZE_NUMBER_INT);
        $desc = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_STRING);

        $this->dayDao->update($day_id, $desc);
        return "Location: ../${block}/";
    }

    /**
     * @GET(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/enrolled$!", sec="admin")
     */
    public function viewEnrollment() {
        global $URI_PARAMS;
        global $VIEW_DATA;

        $course_number = $URI_PARAMS[1];
        $block = $URI_PARAMS[2];

        $offering = $this->offeringDao->getOfferingByCourse($course_number, $block);
        $enrollment = $this->enrollmentDao->getEnrollmentForOffering($offering['id']);

        if ($_SESSION['error']) {
            $VIEW_DATA['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $VIEW_DATA["course"] = $course_number;
        $VIEW_DATA["enrollment"] = $enrollment;
        $VIEW_DATA["block"] = $block;
        $VIEW_DATA["offering_id"] = $offering["id"];
        $VIEW_DATA["title"] = "Enrollment";
        return "enrollment.php";
    }

    /**
     * @POST(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/enrolled$!", sec="admin")
     */
    public function replaceEnrollment() {
        $offering_id = filter_input(INPUT_POST, "offering_id", FILTER_SANITIZE_NUMBER_INT);
        if ($offering_id && $_FILES["list"]) {
            // delete current enrollment
            $this->enrollmentDao->deleteEnrollment($offering_id);

            // parse file for new students
            $this->enrollStudentsInFile($_FILES["list"]["tmp_name"], $offering_id);
        }

        return "Location: enrolled";
    }

    /**
     * @POST(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/enroll$!", sec="admin")
     */
    public function enroll() {
        $offering_id = filter_input(INPUT_POST, "offering_id", FILTER_SANITIZE_NUMBER_INT);
		$studentID = filter_input(INPUT_POST, "studentID", FILTER_SANITIZE_NUMBER_INT);

        $stu_user_id = $this->userDao->getUserIdByStudentId($studentID);
        if ($stu_user_id) {
            $this->enrollmentDao->enroll($stu_user_id, $offering_id);
        } else {
            $_SESSION['error'] = "User with student ID: $studentID not found";
        }
        return "Location: enrolled";
    }

    /**
     * @POST(uri="!^/(cs\d{3})/(20\d{2}-\d{2})/unenroll$!", sec="admin")
     */
    public function unenroll() {
        $offering_id = filter_input(INPUT_POST, "offering_id", FILTER_SANITIZE_NUMBER_INT);
		$stu_user_id = filter_input(INPUT_POST, "uid", FILTER_SANITIZE_NUMBER_INT);
        $this->enrollmentDao->unenroll($stu_user_id, $offering_id);
        return "Location: enrolled";
    }

    private function enrollStudentsInFile($file, $offering_id) {
        $lines = file($file);

        # The CSV file should be formatted like a copy pasted infosys classlist
        foreach($lines as $line) {
        
            # lines that do not start with an index and a studentId are ignored
            if (preg_match("/^\d+\s*,\s*0{3}-[169]\d-\d{4}/", $line)) {
                list($idx, $sid, $first, $middle, $last, $email) = str_getcsv($line);
        
                # create user if not already in DB
                $user_id = $this->userDao->getUserId($email);
                if (!$user_id) {
                    $user_id = $this->createAccount($sid, $first, $middle, $last, $email);
                }
        
                # enroll in the offering
                $this->enrollmentDao->enroll($user_id, $offering_id);
            }
        }
    }

    private function createAccount($sid, $first, $middle, $last, $email) {
        $given = trim($first) . " " . trim($middle);
        $teamsName = trim($given) . " " . trim($last);
        # transform social security formatted student ID into 6 digit 
        $matches = array();
        preg_match("/0{3}-([169]\d)-(\d{4})/", $sid, $matches);
        $id6 = $matches[1] . $matches[2];
        // make initial password be the 6 digit student ID
        $hash = password_hash($id6, PASSWORD_DEFAULT);

        $user_id = $this->userDao->insert($given, $last, $first, 
            $email, $id6, $teamsName, $hash, "user", 1);
    
        # create custom welcome message
        $message = 
"Dear $first $middle $last,

Professor Michael Zijlstra's course has its lecture videos at: https://manalabs.org/videos/

To access these videos the following account has been created for you:

user: $email
pass: $id6

Please do not reply to this email, instead please ask your questions in class!

Enjoy your course,

Manalabs.org Automated Account Creator
";

        #email the user about his newly created account
        $headers ='From: "Manalabs Video System" <videos@manalabs.org> \r\n';
        mail($email, "Prof Zijlstra's manalabs.org account", $message, $headers);
        return $user_id;
    }
}
