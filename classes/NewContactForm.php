<?php
/**
 * Contact Form - handles form submission
 *
 * @author Desmond Rush
 */
class NewContactForm
{
    /**
     * Post Variables
     *
     * @var array
     */
    protected $_aPostVars       = array();
    /**
     * filtered _POST vars
     *
     * @var array
     */
    protected $_aCleanVars      = array();
    /**
     * An array to pass back data.
     *
     * @var array
     */
    protected $_aData           = array();
    /**
     * All error messages.
     *
     * @var array
     */
    protected $_aErrorMessages  = array();
    /**
     * Error configurations
     *
     * @var array|mixed
     */
    protected $_aErrorConfig    = array();
    /**
     * Set to true if form was successfully sent with no errors.
     *
     * @var bool
     */
    private   $_bSuccess        = false;


    const CAPTCHA_QUESTION      = '210-200';
    const CAPTCHA_ANSWER        = '10';


    /**
     * Set _aPostVars with post variables.
     * Set _aCleanVar with scrubbed data.
     * Set _aData with errors and messages.
     *
     * @param $POST
     */
    function __construct(array $POST)
    {
        $this->_aPostVars       = $POST;
        $this->_aErrorConfig    = require_once(__DIR__ . '/../config/contact_form_errors.inc.php');
        $this->_aCleanVars      = filter_var_array($POST, $this->_aErrorConfig['filters']);
        $this->_setDataArray(); // setting with errors and messages.
    }



    /**
     * Sets _aData with errors and messages.
     *
     * @return array
     */
    protected function _setDataArray()
    {
        // Check for errors and set if any.
        $this->_setErrors();
        // Check if it was successful and set
        $this->_isSuccess();
    }



    /**
     * Sets _aErrorMessages array with arrays if any.
     *
     * @return array
     */
    protected function _setErrors()
    {
        foreach($this->_aPostVars as $sKey => $value)
        {
            // Is the var blank?
            if($this->_isBlank($sKey) != true)
            {
                // Not blank, but is it valid?
                $this->_isInvalid($sKey);
            }
        }
    }



    /**
     * Is the var blank? If so match it to an error message by key.
     * Sets _ErrorMessages accordingly.
     *
     * Note: The key passed needs to exist in _aErrorConfig for an error to be set.
     *
     * @param $sKey
     * @return boolean
     */
    protected function _isBlank($sKey)
    {
        if(empty($this->_aPostVars[$sKey]))
        {
            // Variable is Empty, grab error message only if it key exists
            if(isset($this->_aErrorConfig['require'][$sKey]))
            {
                // Set Error Message
                $this->_aErrorMessages[$sKey] = $this->_aErrorConfig['require'][$sKey];

                return true;
            }
        }

        return false;
    }



    /**
     * Is the filter var invalid? If so, set an error message.
     *
     * Note: The key passed needs to exist in _aErrorConfig for an error to be set.
     *
     * @param $sKey
     * @return boolean
     */
    protected function _isInvalid($sKey)
    {
        if($this->_aCleanVars[$sKey] === false)
        {
            $this->_aErrorMessages[$sKey] = $this->_aErrorConfig['invalid'][$sKey]; // Set Error Message

            return true; // no reason to proceed variable is blank.
        }

        // CUSTOM CHECK: for Captcha Answer...
        elseif($sKey == 'captcha')
        {
            if($this->_aPostVars['captcha'] != self::CAPTCHA_ANSWER)
            {
                $this->_aErrorMessages[$sKey] = $this->_aErrorConfig['invalid'][$sKey]; // Set Error Message

                return true; // captcha is invalid
            }
        }

        return false;
    }



    /**
     * No errors equal success.
     *
     * @return boolean
     */
    protected function _isSuccess()
    {
        if (!empty($this->_aErrorMessages))
        {
            // FAILED! Send us errors - Set success to false and set errors.
            $this->_aData['errors']  = $this->_aErrorMessages;
            $this->_aData['success'] = false;
            $this->_bSuccess = false;
        }
        else
        {
            // SUCCESS! No errors - Set success to true and set a message to display success.
            $this->_aData['message'] = $this->_aErrorConfig['message']['success'];
            $this->_aData['success'] = true;
            $this->_bSuccess = true;
        }

        // RETURN
        return $this->_bSuccess;
    }



    /**
     * Encode errors to JSON
     * Returns false if _aData is empty.
     *
     * @return mixed
     */
    protected function _encodeToJSON()
    {
        if(!empty($this->_aData))
        {
            return json_encode($this->_aData);
        }

        return false;
    }


    /**
     * Get Captcha Question.
     *
     * @return string
     */
    public static function getCaptchaQuestion()
    {
        return self::CAPTCHA_QUESTION;
    }



    /**
     * Fetch data array as an Array.
     * Optional: pass the param 'JSON' to fetch the data array as a JSON string.
     *
     * @param string $sEncode
     * @return array|mixed
     */
    public function getData($sEncode = '')
    {
        if($sEncode === 'JSON' OR $sEncode === 'json')
        {
            // RETURN JSON string
            return $this->_encodeToJSON();
        }
        else
        {
            // RETURN array
            return $this->_aData;
        }
    }



    /**
     * Check to see if the Form was successfully validated and clear of errors.
     *
     * @return boolean
     */
    public function getSuccess()
    {
        if($this->_bSuccess === true)
        {
            return true;
        }

        return false;
    }



    /**
     * Pass SimpleMail object
     *
     * @param $oSimpleMail
     */
    public function sendEmail( SimpleMail $oSimpleMail )
    {
        $oSimpleMail->setTo('name@domain.com', 'Admin')
                    ->setSubject('Thank You For Your Interest')
                    ->setFrom('no-reply@domain.com', 'Do Not Reply')
                    ->addMailHeader('Cc', $this->_aCleanVars['email'], 'Recipient')
                    ->addMailHeader('Reply-To', 'no-reply@domain.com', 'Do Not Reply')
                    ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
                    ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
                    ->setMessage('<strong>Your message has been sent.</strong><br /><br />'.
                                 'This is what you sent.'. '<br />'.
                                 'Name: '.    $this->_aCleanVars['name']. '<br />'.
                                 'Company: '. $this->_aCleanVars['company']. '<br />'.
                                 'Message: '. $this->_aCleanVars['comment'])
                    ->setWrap(78);

        // echo $oSimpleMail->debug();

        if ($oSimpleMail->send() == true)
        {
            $this->_aData['email_success']  = true;
            $this->_aData['message']        = $this->_aErrorConfig['message']['email_success'];
        }
        else
        {
            $this->_aData['email_success']  = false;
            $this->_aData['message']        = $this->_aErrorConfig['message']['email_failed'];
        }
    }
}
