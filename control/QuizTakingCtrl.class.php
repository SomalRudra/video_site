<?php

/**
 * Quiz Taking Controller Class
 * @author mzijlstra 12/21/2022
 * 
 * @Controller
 */
class QuizTakingCtrl {
    /**
     * @Inject('QuizDao')
     */
    public $quizDao;

    /**
     * @Inject('QuestionDao')
     */
    public $questionDao;

    /**
     * @Inject('AnswerDao')
     */
    public $answerDao;

    /**
     * @Inject('QuizEventDao')
     */
    public $quizEventDao;

    /**
     * @Inject('MarkdownHlpr')
     */
    public $markdownCtrl;

    /**
     * @Inject('ImageHlpr')
     */
    public $imageCtrl;


    /**
     * This function is really a 3 in one. 
     * 1. If it is used before the start time it shows a countdown timer
     * 2. If it is used after the stop time it shows a status for each question
     * 3. If between start and stop the user can give answers
     * 
     * @GET(uri="!^/([a-z]{2,3}\d{3,4})/(20\d{2}-\d{2}[^/]*)/quiz/(\d+)$!", sec="observer")
     */
    public function viewQuiz() {
        global $URI_PARAMS;
        global $VIEW_DATA;

        $course = $URI_PARAMS[1];
        $block = $URI_PARAMS[2];
        $quiz_id = $URI_PARAMS[3];

        $quiz = $this->quizDao->byId($quiz_id);
        $tz = new DateTimeZone(TIMEZONE);
        $now = new DateTimeImmutable("now", $tz);
        $start = new DateTimeImmutable($quiz['start'], $tz);
        $stop = new DateTimeImmutable($quiz['stop'], $tz);

        $startDiff = $now->diff($start);
        $stopDiff = $now->diff($stop);

        $user_id = $_SESSION['user']['id'];
        $VIEW_DATA['course'] = $course;
        $VIEW_DATA['block'] = $block;
        $VIEW_DATA['quiz'] = $quiz;
        if ($startDiff->invert === 0) { // start is in the future
            // show countdown page
            $VIEW_DATA['title'] = "Quiz Countdown";
            $VIEW_DATA['start'] = $startDiff;    
            return "quiz/countdown.php";
        } else if ($this->quizEnded($quiz_id)) { 
            // show quiz taken status page
            $VIEW_DATA['title'] = "Quiz Results";
            $VIEW_DATA["parsedown"] = new Parsedown();
            $VIEW_DATA['questions'] = $this->questionDao->forQuiz($quiz_id);
            $VIEW_DATA['answers'] = $this->answerDao->forUser($user_id, $quiz_id);
            $VIEW_DATA['possible'] = $this->sumPoints($VIEW_DATA['questions']);
            $VIEW_DATA['received'] = $this->sumPoints($VIEW_DATA['answers']);
            return "quiz/results.php";
        } else { // the quiz is open
            $this->quizEventDao->add($quiz_id, $user_id, "start");
            // show the actual quiz page
            $VIEW_DATA['title'] = "Quiz";
            $VIEW_DATA["parsedown"] = new Parsedown();
            $VIEW_DATA['questions'] = $this->questionDao->forQuiz($quiz_id);
            $VIEW_DATA['stop'] = $stopDiff;
            $VIEW_DATA['answers'] = $this->answerDao->forUser($user_id, $quiz_id);
            return "quiz/doQuiz.php";
        }
    }

    /**
     * Expects AJAX
     * 
     * @POST(uri="!^/([a-z]{2,3}\d{3,4})/(20\d{2}-\d{2}[^/]*)/quiz/(\d+)/question/(\d+)/markdown$!", sec="observer")
     */
    public function answerMarkdownQuestion() {
        global $URI_PARAMS;

        // reject answers after quiz stop time
        $quiz_id = $URI_PARAMS[3];
        if ($this->quizEnded($quiz_id, 180)) { 
            return "error/403.php";
        }

        $question_id = $URI_PARAMS[4];
        $user_id = $_SESSION['user']['id'];
        $answer_id = filter_input(INPUT_POST, "answer_id", FILTER_VALIDATE_INT);
        $shifted = filter_input(INPUT_POST, "answer");

        $answer = $this->markdownCtrl->ceasarShift($shifted);

        if ($answer_id) {
            $this->answerDao->update($answer_id, $answer, $user_id);
        } else {
            $answer_id = $this->answerDao->add($answer, $question_id, $user_id);
        }
        return [ 'answer_id' => $answer_id ];
    }

    /**
     * Expects AJAX
     * 
     * @POST(uri="!^/([a-z]{2,3}\d{3,4})/(20\d{2}-\d{2}[^/]*)/quiz/(\d+)/question/(\d+)/image$!", sec="observer")
     **/
    public function answerImageQuestion() {
        global $URI_PARAMS;

        $quiz_id = $URI_PARAMS[3];
        $question_id = $URI_PARAMS[4];

        // reject answers after quiz stop time
        if ($this->quizEnded($quiz_id, 180)) { 
            return "error/403.php";
        }

        $user_id = $_SESSION['user']['id'];
        $answer_id = filter_input(INPUT_POST, "answer_id", FILTER_VALIDATE_INT);
        $res = $this->imageCtrl->process("image", $question_id, $user_id);

        if (isset($res['error'])) {
            return $res;
        } else {
            $dst = $res['dst'];
        }

        // create / update answer in the db
        if ($answer_id) {
            $this->answerDao->update($answer_id, $dst, $user_id);
        } else {
            $answer_id = $this->answerDao->add($dst, $question_id, $user_id);
        }

        return [ "dst" => $dst, "answer_id" => $answer_id ];
    } 

    /**
     * @POST(uri="!^/([a-z]{2,3}\d{3,4})/(20\d{2}-\d{2}[^/]*)/quiz/(\d+)/finish$!", sec="observer")
     */
    public function finishQuiz() {
        global $URI_PARAMS;

        $quiz_id = $URI_PARAMS[3];
        $user_id = $_SESSION['user']['id'];
        $this->quizEventDao->add($quiz_id, $user_id, "stop");

        return "Location: ../../quiz";
    }

    private function quizEnded($quiz_id, $leewaySecs = 0) {
        $quiz = $this->quizDao->byId($quiz_id);
        $quiz = $this->quizDao->byId($quiz_id);
        $tz = new DateTimeZone(TIMEZONE);
        $now = new DateTimeImmutable("now", $tz);
        $stop = new DateTimeImmutable($quiz['stop'], $tz);
        // give leeway second 
        $stop = $stop->add(new DateInterval("PT{$leewaySecs}S"));
        $stopDiff = $now->diff($stop);
        return $stopDiff->invert == 1; // is it in the past?
    }

    private function sumPoints($array) {
        $result = 0;
        foreach ($array as $item) {
            $result += $item['points'];
        }
        return $result;
    }

}
?>