<?php
    /**
     * This logic should be in a Controller.
     */
    require_once('./classes/NewContactForm.php');
    $sCaptchaQuestion = NewContactForm::CAPTCHA_QUESTION;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interview Example</title>
    <meta charset="utf-8">
    <link href='./css/base.css' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900' rel='stylesheet' type='text/css' media='all' />
    <link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css' />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> <!-- load jquery via CDN -->
    <script src="./js/ajax_form.js"></script>
</head>
<body>
    <article id="main-article">
        <section>
            <form id="drush-form" action="#" method="POST">
                <fieldset>
                    <legend>AJAX FORM</legend>
                    <!-- NAME -->
                    <div id="name-group" class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="name here">
                        <!-- error will go here -->
                    </div>
                    <!-- EMAIL -->
                    <div id="email-group" class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="email@example.com">
                        <!-- error will go here -->
                    </div>
                    <!-- COMPANY -->
                    <div id="company-group" class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" placeholder="company name here">
                        <!-- error will go here -->
                    </div>
                    <!-- HUMAN TEST -->
                    <div id="captcha-group" class="form-group">
                        <label for="captcha">What is <?php echo $sCaptchaQuestion ?></label>
                        <input type="text" id="captcha" name="captcha" placeholder="please solve the math problem">
                        <!-- error will go here -->
                    </div>
                    <!-- COMMENT -->
                    <div id="comment-group" class="form-group">
                        <label for="comment">Message</label>
                        <textarea id="comment" name="comment" placeholder="Please Leave a Message"></textarea>
                        <!-- error will go here -->
                    </div>

                    <button type="submit" class="btn submit">Submit</button>
                </fieldset>
            </form>
        </section>
    </article>
</body>
</html>