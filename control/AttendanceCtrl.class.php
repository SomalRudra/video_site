<?php
/**
 * Attendance Controller Class
 * @author mzijlstra 2021-11-29
 *
 * @Controller
 */
class AttendanceCtrl {
    /**
     * @Inject("MeetingDao")
     */
    public $meetingDao;
    /**
     * @Inject("AttendanceDataDao")
     */
    public $attendanceDataDao;
    /**
     * @Inject("AttendanceDao")
     */
    public $attendanceDao;
    /**
     * @Inject("VideoCtrl")
     */
    public $videoCtrl;
    /**
     * @Inject("EnrollmentDao")
     */
    public $enrollmentDao;
    /**
     * @Inject("DayDao")
     */
    public $dayDao;
    /**
     * @Inject("OfferingDao")
     */
    public $offeringDao;


    /**
	 * @GET(uri="|^/(cs\d{3})/(20\d{2}-\d{2})/attendance$|", sec="admin");
	 */
    public function overview() {
        global $URI_PARAMS;
		global $VIEW_DATA;

		$course_num = $URI_PARAMS[1];
		$block = $URI_PARAMS[2];

        // We're going to build on top of offering overview -- run it first
        // this populates $VIEW_DATA with the overview related data
        $this->videoCtrl->offering();

        // Add attendance data
        $meetings = $this->meetingDao->allForOffering($VIEW_DATA["offering"]["id"]);
        //var_dump($meetings);

        $days = $VIEW_DATA["days"];
        foreach ($meetings as $meeting) {
            if (!array_key_exists("meetings", $days[$meeting["abbr"]])) {
                $days[$meeting["abbr"]]["meetings"] = [];
            }
            $days[$meeting["abbr"]]["meetings"][] = $meeting;
        }
        $VIEW_DATA["days"] = $days;

        return "attendance.php";
    }

    /**
     * @POST(uri="|^/(cs\d{3})/(20\d{2}-\d{2})/attendance$|", sec="admin");
     */
    public function addMeeting() {
        $day_id = filter_input(INPUT_POST, "day_id", FILTER_SANITIZE_NUMBER_INT);
        if ($day_id && $_FILES["list"]) { 
            $this->parseMeetingFile($_FILES["list"]["tmp_name"], $day_id);
        }

        return "Location: attendance";
    }

    private function parseMeetingFile($file, $day_id) {
        // meeting weight for weekly in-class requirement
        $weight = 0.5; // international students need '2 sessions' per week

        // prepare file contents
        $text = mb_convert_encoding(file_get_contents($file), "UTF-8", "UTF-16LE");
        $lines = explode("\n", $text);

        // gather meeting data 
        $title = trim(str_getcsv($lines[2], "\t")[1]);
        $fields = str_getcsv($lines[3], "\t");
        $date = $this->toIsoDate($fields[1]);

        if (count($fields) == 3) {
            $meeting_start = $fields[2];
        } else {
            $meeting_start = $this->to24hour($fields[1]);
        }
        $fields = str_getcsv($lines[4], "\t");
        if (count($fields) == 3) {
            $meeting_stop = $fields[2];
        } else {
            $meeting_stop = $this->to24hour($fields[1]);
        }

        // insert meeting into DB 
        $meeting_id = $this->meetingDao->add($day_id, $title, $date, 
            $meeting_start, $meeting_stop, $weight);

        // insert attendance lines
        for ($i = 7; $i < count($lines) -1; $i++) {
            list($name, $start, $stop, $duration, $email, $role) = 
                str_getcsv($lines[$i], "\t");
            $start = $this->to24hour($start);
            $stop = $this->to24hour($stop);

            $this->attendanceDataDao->add($meeting_id, $name, $start, $stop);
        }

        // generate report
        $day = $this->dayDao->get($day_id);
        $this->generateReport($day["offering_id"], $meeting_id);
    }

    private function generateReport($offering_id, $meeting_id) {
        // error margin -- how many minutes students can be late without trouble
        $margin = 3 * 60; // 3 minutes

        // get initial data
        $attendants = $this->attendanceDataDao->forMeeting($meeting_id);
        $enrolled = $this->enrollmentDao->getEnrollmentForOffering($offering_id);

        // put enrolled in hashmap for quick lookup
        $enrollment = [];
        foreach ($enrolled as $student) {
            $enrollment[$student["teamsName"]]  = true;
        }

        // find notEnrolled attendants (while constructing attendance array)
        $attendance = [];
        foreach ($attendants as $attendant) {
            $attendance[$attendant["teamsName"]] = ["notEnrolled" => 0, 
                                                    "absent" => 0,
                                                    "arriveLate" => 0,
                                                    "leaveEarly" => 0,
                                                    "middleMissing" => 0];

            if (!$enrollment[$attendant["teamsName"]]) {
                $attendance[$attendant["teamsName"]]["notEnrolled"] = 1;
            }
        }

        // find / add absent students
        foreach ($enrolled as $student) {
            if (!$attendance[$student["teamsName"]]) {
                $attendance[$student["teamsName"]] = ["notEnrolled" => 0, 
                                                    "absent" => 1,
                                                    "arriveLate" => 0,
                                                    "leaveEarly" => 0,
                                                    "middleMissing" => 0];
            }
        }

        // mark those that arrived late and those that left early
        $attendants = $this->attendanceDataDao->uniqueUsersForMeeting($meeting_id);

        $meeting_start = strtotime($meeting_start) + $margin;
        $meeting_stop = strtotime($meeting_stop) - $margin;
        
        foreach ($attendants as $attendant) {
            if (strtotime($attendant["start"]) > $meeting_start) {
                $attendance[$attendant["teamsName"]]["arriveLate"] = 1;
            }
            if (strtotime($attendant["stop"]) < $meeting_stop) {
                $attendance[$attendant["teamsName"]]["leaveEarly"] = 1;
            }
        }

        // for those with multiple entrires check if middle missing 
        // by checking for a lack of duration
        $attendants = $this->attendanceDataDao->multiEntryForMeeting($meeting_id);
        $meeting_duration = $meeting_stop - $meeting_start;
        foreach ($attendants as $attendant) {
            if ($attendant['duration'] < $meeting_duration) {
                $attendance['teamsName']['middleMissing'] = 1;
            }
        }

        // remove previous report (if in DB)
        $this->attendanceDao->remove($meeting_id);

        // insert attendance report into DB
        $this->attendanceDao->addReport($meeting_id, $attendance);
    }

    private function to24hour($str) {
        $parts = date_parse($str);
        return $parts["hour"] . ":" . $parts["minute"] . ":" . $parts["second"];
    }
    
    private function toIsoDate($str) {
        $parts = date_parse($str);
        return $parts["year"] . "-" . $parts["month"] . "-" . $parts["day"];
    }
    
}