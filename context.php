<?php
/******************************************************************************* 
 * DO NOT MODIFY THIS FILE, IT IS GENERATED 
 * 
 * When DEVELOPMENT=true this file is generated based on the settings in 
 * frontController.php and the annotations found in the class files in the 
 * control and model directories
 ******************************************************************************/
$mappings = array(
	"GET" => array(
		'|^/(cs\d{3})/(20\d{2}-\d{2})/attendance$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@overview'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/meeting/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@getMeeting'],
		'|^/?$|' => 
			['sec' => 'user', 'route' => 'CourseCtrl@showCourses'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/enrolled$|' => 
			['sec' => 'admin', 'route' => 'CourseCtrl@viewEnrollment'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/getQuestion$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@get'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/getReply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@getReply'],
		'|^/.*login$|' => 
			['sec' => 'none', 'route' => 'UserCtrl@getLogin'],
		'|^/user/add$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@addUser'],
		'|^/.*logout$|' => 
			['sec' => 'none', 'route' => 'UserCtrl@logout'],
		'|^/user$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@all'],
		'|^/user/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@details'],
		'|^/user/(\D.*)$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@teamsName'],
		'|^/(cs\d{3})/?$|' => 
			['sec' => 'user', 'route' => 'VideoCtrl@loggedIn'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/$|' => 
			['sec' => 'user', 'route' => 'VideoCtrl@only_day'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/$|' => 
			['sec' => 'user', 'route' => 'VideoCtrl@offering'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/(\d{2})$|' => 
			['sec' => 'user', 'route' => 'VideoCtrl@video'],
		'|^/cs\d{3}/20\d{2}-\d{2}/(W[1-4]D[1-7]/)?start.*$|' => 
			['sec' => 'user', 'route' => 'ViewCtrl@start'],
		'|^/cs\d{3}/20\d{2}-\d{2}/(W[1-4]D[1-7]/)?pdf.*$|' => 
			['sec' => 'user', 'route' => 'ViewCtrl@pdf'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W\dD\d/)?views/(\d+)?$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@views'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/info/?$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@offering_info'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/info/?$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@videos_info'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/viewers$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@offering_viewers'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7]/)+viewers$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@day_viewers'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/\d{2}/viewers$|' => 
			['sec' => 'admin', 'route' => 'ViewCtrl@video_viewers'],
		'|.+/enrollment$|' => 
			['sec' => 'none', 'route' => 'ViewCtrl@enrollemnt'],
	),
	"POST" => array(
		'|^/(cs\d{3})/(20\d{2}-\d{2})/meeting/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@updMeeting'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/meeting/regen/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@regenReport'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/meeting/attend/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@updateAttendance'],
		'|^/cs\d{3}/20\d{2}-\d{2}/meeting/(\d+)/absent$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@markAbsent'],
		'|^/cs\d{3}/20\d{2}-\d{2}/meeting/(\d+)/present$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@markPresent'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/attendance$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@addMeeting'],
		'|^/cs\d{3}/20\d{2}-\d{2}/meeting/(\d+)/delete$|' => 
			['sec' => 'admin', 'route' => 'AttendanceCtrl@deleteMeeting'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/clone$|' => 
			['sec' => 'admin', 'route' => 'CourseCtrl@cloneOffering'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/edit$|' => 
			['sec' => 'admin', 'route' => 'CourseCtrl@editDay'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/enrollment$|' => 
			['sec' => 'admin', 'route' => 'CourseCtrl@replaceEnrollment'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/question$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@add'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/delQuestion$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@del'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/updQuestion$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@update'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/upvote$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@upvote'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/downvote$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@downvote'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/upreply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@upreply'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/downreply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@downreply'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/addReply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@addReply'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/updReply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@updateReply'],
		'|^/cs\d{3}/20\d{2}-\d{2}/W[1-4]D[1-7]/delReply$|' => 
			['sec' => 'user', 'route' => 'QuestionCtrl@delReply'],
		'|^/.*login$|' => 
			['sec' => 'none', 'route' => 'UserCtrl@login'],
		'|^/user$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@create'],
		'|^/user/(\d+)$|' => 
			['sec' => 'admin', 'route' => 'UserCtrl@update'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/autoplay$|' => 
			['sec' => 'user', 'route' => 'VideoCtrl@autoplay'],
		'|^/(cs\d{3})/(20\d{2}-\d{2})/(W[1-4]D[1-7])/stop$|' => 
			['sec' => 'none', 'route' => 'ViewCtrl@stop'],
		'|^/cs\d{3}/20\d{2}-\d{2}/(W[1-4]D[1-7]/)?speed$|' => 
			['sec' => 'user', 'route' => 'ViewCtrl@speed'],
	),
);
class Context {
    private $objects = array();
    
    public function __construct() {
        $db = new PDO("mysql:dbname=cs472;host=localhost", "cs472dbuser", "WAP Passwd");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->objects["DB"] = $db;
    }

    public function get($id) {
        if ($id === "AttendanceDao" && !isset($this->objects["AttendanceDao"])) {
            $this->objects["AttendanceDao"] = new AttendanceDao();
            $this->objects["AttendanceDao"]->db = $this->get("DB");
        }
        if ($id === "AttendanceDataDao" && !isset($this->objects["AttendanceDataDao"])) {
            $this->objects["AttendanceDataDao"] = new AttendanceDataDao();
            $this->objects["AttendanceDataDao"]->db = $this->get("DB");
        }
        if ($id === "CourseDao" && !isset($this->objects["CourseDao"])) {
            $this->objects["CourseDao"] = new CourseDao();
            $this->objects["CourseDao"]->db = $this->get("DB");
        }
        if ($id === "DayDao" && !isset($this->objects["DayDao"])) {
            $this->objects["DayDao"] = new DayDao();
            $this->objects["DayDao"]->db = $this->get("DB");
        }
        if ($id === "EnrollmentDao" && !isset($this->objects["EnrollmentDao"])) {
            $this->objects["EnrollmentDao"] = new EnrollmentDao();
            $this->objects["EnrollmentDao"]->db = $this->get("DB");
        }
        if ($id === "MeetingDao" && !isset($this->objects["MeetingDao"])) {
            $this->objects["MeetingDao"] = new MeetingDao();
            $this->objects["MeetingDao"]->db = $this->get("DB");
        }
        if ($id === "OfferingDao" && !isset($this->objects["OfferingDao"])) {
            $this->objects["OfferingDao"] = new OfferingDao();
            $this->objects["OfferingDao"]->db = $this->get("DB");
        }
        if ($id === "QuestionDao" && !isset($this->objects["QuestionDao"])) {
            $this->objects["QuestionDao"] = new QuestionDao();
            $this->objects["QuestionDao"]->db = $this->get("DB");
        }
        if ($id === "QuestionVoteDao" && !isset($this->objects["QuestionVoteDao"])) {
            $this->objects["QuestionVoteDao"] = new QuestionVoteDao();
            $this->objects["QuestionVoteDao"]->db = $this->get("DB");
        }
        if ($id === "ReplyDao" && !isset($this->objects["ReplyDao"])) {
            $this->objects["ReplyDao"] = new ReplyDao();
            $this->objects["ReplyDao"]->db = $this->get("DB");
        }
        if ($id === "ReplyVoteDao" && !isset($this->objects["ReplyVoteDao"])) {
            $this->objects["ReplyVoteDao"] = new ReplyVoteDao();
            $this->objects["ReplyVoteDao"]->db = $this->get("DB");
        }
        if ($id === "UserDao" && !isset($this->objects["UserDao"])) {
            $this->objects["UserDao"] = new UserDao();
            $this->objects["UserDao"]->db = $this->get("DB");
        }
        if ($id === "VideoDao" && !isset($this->objects["VideoDao"])) {
            $this->objects["VideoDao"] = new VideoDao();
        }
        if ($id === "ViewDao" && !isset($this->objects["ViewDao"])) {
            $this->objects["ViewDao"] = new ViewDao();
            $this->objects["ViewDao"]->db = $this->get("DB");
        }
        if ($id === "AttendanceCtrl" && !isset($this->objects["AttendanceCtrl"])) {
            $this->objects["AttendanceCtrl"] = new AttendanceCtrl();
            $this->objects["AttendanceCtrl"]->meetingDao = $this->get("MeetingDao");
            $this->objects["AttendanceCtrl"]->attendanceDataDao = $this->get("AttendanceDataDao");
            $this->objects["AttendanceCtrl"]->attendanceDao = $this->get("AttendanceDao");
            $this->objects["AttendanceCtrl"]->videoCtrl = $this->get("VideoCtrl");
            $this->objects["AttendanceCtrl"]->enrollmentDao = $this->get("EnrollmentDao");
            $this->objects["AttendanceCtrl"]->dayDao = $this->get("DayDao");
            $this->objects["AttendanceCtrl"]->offeringDao = $this->get("OfferingDao");
        }
        if ($id === "CourseCtrl" && !isset($this->objects["CourseCtrl"])) {
            $this->objects["CourseCtrl"] = new CourseCtrl();
            $this->objects["CourseCtrl"]->courseDao = $this->get("CourseDao");
            $this->objects["CourseCtrl"]->offeringDao = $this->get("OfferingDao");
            $this->objects["CourseCtrl"]->videoDao = $this->get("VideoDao");
            $this->objects["CourseCtrl"]->dayDao = $this->get("DayDao");
            $this->objects["CourseCtrl"]->enrollmentDao = $this->get("EnrollmentDao");
            $this->objects["CourseCtrl"]->userDao = $this->get("UserDao");
        }
        if ($id === "QuestionCtrl" && !isset($this->objects["QuestionCtrl"])) {
            $this->objects["QuestionCtrl"] = new QuestionCtrl();
            $this->objects["QuestionCtrl"]->questionDao = $this->get("QuestionDao");
            $this->objects["QuestionCtrl"]->questionVoteDao = $this->get("QuestionVoteDao");
            $this->objects["QuestionCtrl"]->replyDao = $this->get("ReplyDao");
            $this->objects["QuestionCtrl"]->replyVoteDao = $this->get("ReplyVoteDao");
            $this->objects["QuestionCtrl"]->userDao = $this->get("UserDao");
        }
        if ($id === "UserCtrl" && !isset($this->objects["UserCtrl"])) {
            $this->objects["UserCtrl"] = new UserCtrl();
            $this->objects["UserCtrl"]->userDao = $this->get("UserDao");
        }
        if ($id === "VideoCtrl" && !isset($this->objects["VideoCtrl"])) {
            $this->objects["VideoCtrl"] = new VideoCtrl();
            $this->objects["VideoCtrl"]->courseDao = $this->get("CourseDao");
            $this->objects["VideoCtrl"]->offeringDao = $this->get("OfferingDao");
            $this->objects["VideoCtrl"]->dayDao = $this->get("DayDao");
            $this->objects["VideoCtrl"]->enrollmentDao = $this->get("EnrollmentDao");
            $this->objects["VideoCtrl"]->questionDao = $this->get("QuestionDao");
            $this->objects["VideoCtrl"]->replyDao = $this->get("ReplyDao");
            $this->objects["VideoCtrl"]->videoDao = $this->get("VideoDao");
        }
        if ($id === "ViewCtrl" && !isset($this->objects["ViewCtrl"])) {
            $this->objects["ViewCtrl"] = new ViewCtrl();
            $this->objects["ViewCtrl"]->viewDao = $this->get("ViewDao");
            $this->objects["ViewCtrl"]->enrollmentDao = $this->get("EnrollmentDao");
            $this->objects["ViewCtrl"]->offeringDao = $this->get("OfferingDao");
            $this->objects["ViewCtrl"]->courseDao = $this->get("CourseDao");
            $this->objects["ViewCtrl"]->dayDao = $this->get("DayDao");
            $this->objects["ViewCtrl"]->userDao = $this->get("UserDao");
            $this->objects["ViewCtrl"]->videoDao = $this->get("VideoDao");
        }
        return $this->objects[$id];
    } // close get method
} // close Context class