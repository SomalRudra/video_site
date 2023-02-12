<?php

/**
 * @author mzijlstra 08/17/2022
 * @Repository
 */

class QuizDao {
    /**
     * @Inject("DB")
     */
    public $db;

    /**
     * @Inject("QuestionDao")
     */
    public $questionDao;

    /**
     * @Inject("EnrollmentDao")
     */
    public $enrollmentDao;

    public function allForOffering($offering_id) {
        $stmt = $this->db->prepare(
			"SELECT q.id, q.name, q.visible, d.abbr
            FROM quiz AS q
            JOIN day AS d ON q.day_id = d.id
            JOIN offering AS o ON d.offering_id = o.id
            WHERE o.id = :offering_id
            AND o.active = 1" 
		);
		$stmt->execute(array("offering_id" => $offering_id));
		return $stmt->fetchAll();
    }

    public function visibleForOffering($offering_id) {
        $stmt = $this->db->prepare(
            "SELECT q.id, q.name, q.visible, d.abbr
            FROM quiz AS q
            JOIN day AS d ON q.day_id = d.id
            JOIN offering AS o ON d.offering_id = o.id
            WHERE o.id = :offering_id 
            AND q.visible = 1
            AND o.active = 1"
		);
		$stmt->execute(array("offering_id" => $offering_id));
		return $stmt->fetchAll();
    }

    public function add($name, $day_id, $start, $stop) {
		$stmt = $this->db->prepare(
			"INSERT INTO quiz 
			VALUES(NULL, :name, :day_id, :start, :stop, 0)"
		);
		$stmt->execute(array(
            "name" => $name,
            "day_id" => $day_id,
            "start" => $start,
            "stop" => $stop
		));
		return $this->db->lastInsertId();
    }

    public function byId($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quiz
            WHERE id = :id");
        $stmt->execute(array("id" => $id));
        return $stmt->fetch();
    }

    public function setStatus($id, $visible) {
        $stmt = $this->db->prepare(
			"UPDATE quiz 
            SET `visible` = :visible
            WHERE id = :id"
		);
		$stmt->execute(array(
            "id" =>  $id, 
            "visible" => $visible,
        ));
    }

    public function update($id, $day_id, $name, $start, $stop) {
        $stmt = $this->db->prepare(
			"UPDATE quiz 
            SET day_id = :day_id, `name` = :name, `start` = :start, `stop` = :stop
            WHERE id = :id"
		);
		$stmt->execute(array(
            "id" =>  $id, 
            "day_id" => $day_id,
            "name" => $name,
            "start" => $start,
            "stop" => $stop
        ));
    }

    public function delete($id) {
        $stmt = $this->db->prepare(
			"DELETE FROM quiz 
            WHERE id = :id"
		);
		$stmt->execute(array("id" =>  $id));
    }

    /**
     * Gets a report of quiz totals for all students enrolled in this offering
     */
    public function report($offering_id) {
        // TODO move the logic in this method back into the controller

        // get enrollment for offering
        $enrolled = $this->enrollmentDao->getEnrollmentForOffering($offering_id);

        // create data two dimensional array and initialize first 3 columns
        $data = [];
        foreach ($enrolled as $user) {
            $data[$user['id']] = [];
            $data[$user['id']][] = $user['studentID'];
            $data[$user['id']][] = $user['firstname'];
            $data[$user['id']][] = $user['lastname'];
        }

        // get all quizzes for offering
        $stmt = $this->db->prepare(
            "SELECT q.id, q.name, d.abbr
            FROM quiz AS q
            JOIN `day` AS d ON q.day_id = d.id
            WHERE d.offering_id = :offering_id
            ORDER BY q.start");
        $stmt->execute(array("offering_id" => $offering_id));
        $quizzes = $stmt->fetchAll();

        // build CSV header and query for data fetching 
        $count= 1;
        $header = '"studentId","firstName","lastName",';
        foreach ($quizzes as $quiz) {
            // build CSV header line
            $header .= '"' . $quiz['abbr'] . '",';

            // build data column for this quiz
            $pts = $this->getQuizTotalsForEnrolled($quiz['id'], $offering_id);
            foreach ($pts as $pt) {
                $data[$pt['user_id']][] = $pt['points'];
            }
            $count++;
        }

        return [
            "colCount" => ($count + 3), // 3 are: sid, first, last
            "header" => $header,
            "data" => $data,
        ];
    }

    /**
     * Clones all quizzes for an offering (which is being cloned)
     */
    public function clone($offering_id, $new_offering_id) {
        // TODO move the logic in this method back into the controller

        // find difference in days between the two offerings
        $inject = "{$offering_id}, {$new_offering_id}";
        $stmt = $this->db->prepare(
            "SELECT id, `start` FROM offering
            WHERE id IN ({$inject})
            ORDER BY `start`");
        $stmt->execute(array("offering_id" => $new_offering_id));
        $dates = $stmt->fetchAll();
        $earlier = new DateTime(substr($dates[0]['start'], 0, 10));
        $later = new DateTime(substr($dates[1]['start'], 0, 10));
        $daysDiff = $earlier->diff($later)->format("%r%a");
        $interval = new DateInterval("P{$daysDiff}D");

        // create a lookup table for abbr to new day id
        $stmt = $this->db->prepare(
            "SELECT id, abbr FROM day
            WHERE offering_id = :offering_id");
        $stmt->execute(array("offering_id" => $new_offering_id));
        $rows = $stmt->fetchAll();

        $days = [];
        foreach ($rows as $row) {
            $days[$row['abbr']] = $row['id'];
        }

        // get all the old quizzes
        $stmt = $this->db->prepare(
            "SELECT q.id, q.name, q.start, q.stop, d.abbr FROM quiz AS q
            JOIN `day` AS d on q.day_id = d.id
            WHERE d.offering_id = :offering_id");
        $stmt->execute(array("offering_id" => $offering_id));
        $quizzes = $stmt->fetchAll();

        // create a clone for each on the same day in the new offering
        $stmt = $this->db->prepare(
			"INSERT INTO quiz 
			VALUES(NULL, :name, :day_id, :start, :stop, :visible)"
		);
        foreach ($quizzes as $quiz) {
            // move start date by date difference between offerings
            $start = new DateTime($quiz['start']);
            $stop = new DateTime($quiz['stop']);
            $start->add($interval);
            $stop->add($interval);

            $stmt->execute(array(
                "name" => $quiz['name'],
                "day_id" => $days[$quiz['abbr']],
                "start" => $start->format("Y-m-d H:i:s"),
                "stop" => $stop->format("Y-m-d H:i:s"),
                "visible" => $quiz['visible'],
            ));
            $new_quiz_id = $this->db->lastInsertId();

            // also clone the questions for each quiz
            $this->questionDao->clone($quiz['id'], $new_quiz_id);
        }
    }

    private function getQuizTotalsForEnrolled($quiz_id, $offering_id) {
        $stmt = $this->db->prepare(
			"SELECT e.user_id, sum(a.points) AS points
            FROM enrollment AS e 
            LEFT JOIN answer AS a ON a.user_id = e.user_id 
            LEFT JOIN question AS q ON a.question_id = q.id
            WHERE e.offering_id = :offering_id
            AND q.quiz_id = :quiz_id 
            GROUP BY a.user_id "
		);
		$stmt->execute(array(
            "quiz_id" => $quiz_id,
            "offering_id" => $offering_id
        ));
		return $stmt->fetchAll();
    }
}
?>