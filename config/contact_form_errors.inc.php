<?php
/**
 * This is a config file for Errors/Messages.
 * Used by the following: ./classes/NewContactForm
 */
return array
    (
        'filters'   => array
        (
            'email'     => FILTER_VALIDATE_EMAIL,
            'name'      => array(
                                    'filter'    => FILTER_SANITIZE_STRING,
                                    'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES
                                ),
            'company'   => array(
                                    'filter'    => FILTER_SANITIZE_STRING,
                                    'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES
                                ),
            'comment'   => array(
                                    'filter'    => FILTER_SANITIZE_STRING,
                                    'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES
                                ),
            'captcha'   => array(
                                    'filter'    => FILTER_SANITIZE_STRING,
                                    'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES
                                )
        ),

        'require' => array
        (
            'name'      => 'Name is required.',
            // 'company'   => can be blank,
            'email'     => 'Email is required.',
            'comment'   => 'Message is required.',
            'captcha'   => 'Question is required to verify that you are not a web bot.'
        ),

        'invalid' => array
        (
            'email'     => 'Not a valid Email',
            'captcha'   => 'That is not the right answer, please try again.'
        ),

        'message' => array
        (
            'success'       => 'Your Message Has Been Sent!',
            'email_success' => 'Email was sent successfully!',
            'email_failed'  => 'An error occurred. We could not send an email. '.
                               'Please contact me@desmondrush.com if this continues to happen.'
        )
    );