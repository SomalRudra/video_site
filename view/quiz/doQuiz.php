<!DOCTYPE html>
<html>
    <head>
        <title>Quiz: <?= $quiz['name'] ?></title>
        <meta charset="utf-8" />
        <meta name=viewport content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="res/css/lib/font-awesome-all.min.css">
        <link rel="stylesheet" href="res/css/common-1.1.css">
        <link rel="stylesheet" href="res/css/adm.css">
        <link rel="stylesheet" href="res/css/lib/prism.css">
        <link rel="stylesheet" href="res/css/quiz-1.3.css">
        <script src="res/js/lib/prism.js"></script>
        <script src="res/js/markdown.js"></script>
        <script src="res/js/quiz/countdown-1.1.js"></script>
        <script src="res/js/quiz/quiz-1.2.js"></script>
    </head>
    <body>
        <?php include("header.php"); ?>
        <main>
            <nav id="back" class="back" title="Back">
                <i class="fa-solid fa-arrow-left"></i>
            </nav>
            <nav class="tools">
                <h3><span id="hours"><?= $stop->format("%H") ?></span>:<span id="minutes"><?= $stop->format("%I") ?></span>:<span id="seconds"><?= $stop->format("%S") ?></span></h3>
            </nav>
            <div id="content">
                <div class="quiz">
                    <div id="quiz_id" class="status" data-id="<?= $quiz['id']?>">
                       <h1><?= $quiz['name']?></h1> 
                    </div>
                </div>

                <?php foreach ($questions as $question): ?>
                    <div class="qcontainer">
                        <div class="about">
                            <div class="seq"><?= $question['seq'] ?></div>
                            <div class="points">Points: <?= $question['points'] ?></div>
                        </div>
                        <div class="question" data-id="<?= $question['id']?>">
                            <div class="qType" data-type="<?= $question['type'] ?>">Type: <?= $question['type'] == 'markdown' ? "Markdown Text" : "Image Upload" ?></div>
                            <div>Question Text:</div> 
                            <div class="questionText">
                                <?= $parsedown->text($question['text']) ?>
                            </div>
                            <div>Your Answer:</div> 
                            <?php if($question['type'] == "plain_text"): ?>
                            <textarea class="answer" data-id="<?= $answers[$question['id']]['id'] ?>" placeholder="Write your answer here"><?= $answers[$question['id']]['text']?></textarea>
                            <?php elseif($question['type'] == 'markdown'): ?>
                            <textarea class="answer" data-id="<?= $answers[$question['id']]['id'] ?>" placeholder="Use **markdown** syntax in your text like:&#10;&#10;```javascript&#10;const code = &quot;highlighted&quot;&semi;&#10;```"><?= $answers[$question['id']]['text']?></textarea>
                            <div>
                                <div class="preview"><button tabindex="-1" class="previewBtn">Preview Markdown</button></div>
                                <div class="previewArea"></div>
                            </div>
                            <?php elseif ($question['type'] == "image"): ?>
                                <?php if ($answers[$question['id']]): ?>
                                    <img class="answer" data-id="<?= $answers[$question['id']]['id'] ?>" src="<?= $answers[$question['id']]['text'] ?>" />
                                <?php else: ?>
                                    <img class="answer hide" />
                                <?php endif; ?>
                            <div>
                                <?php if ($answers[$question['id']]): ?>
                                    <label>Upload Replacement: </label>
                                <?php else: ?>
                                    <label>Upload Answer: </label>
                                <?php endif; ?>
                                <input type="file" class="img_replace" />
                                <i class="fa-solid fa-circle-notch"></i>
                            </div>
                            
                            <?php endif; ?>    
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="done">
                    <div class="note">Answers are saved automatically</div>
                    <form method="POST" action="<?= $quiz['id'] ?>/finish">
                        <button id="finish">Finish Quiz</button>
                    </form>
                </div>
            </div>
        </main>
    </body>
</html>
