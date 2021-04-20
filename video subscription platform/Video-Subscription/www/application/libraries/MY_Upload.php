<?php
/**
 * Extended Codeigniter Upload library and added funcions to upload file to S3 Bucket.
 * 
 * @package    CodeIgniter AWS S3 Integration Library
 * @author     scriptigniter <scriptigniter@gmail.com>
 * @link       http://www.scriptigniter.com/cis3demo/
 */

class MY_Upload extends CI_Upload
{
    public $source_path = "";
    public $s3_path = "";
    public $file_resource = "";
    public $original_file_name = "";
    
    /**
    * append unique number to avoid overwritten of file
    *
    * @var	boolean
    */
    public $make_unique_filename = true;
    
    /**
    * ACL for file
    *
    * @var	string
    */
    public $acl = "public-read";    
	
    public function __construct($props = array())
    {       
        parent::__construct($props);
    }
    
    public function validate_upload_path()
    {
        if ($this->upload_path == '') {
            $this->set_error('upload_no_filepath');
            return FALSE;
        }
        
        //Create upload path if it does not exists
        if (!file_exists($this->upload_path)) {
            umask(0); //reference http://stackoverflow.com/questions/6449154/php-mkdir-0777-becomes-0755
            if (!mkdir($this->upload_path, 0777, true)) {
                //die('Failed to create folders...');
                //no need of die as if this path does not exists then next conditions will throw an error
            }
        }
        
        if (!@is_dir($this->upload_path)) {
            $this->set_error('upload_no_filepath');
            return FALSE;
        }
        
        if (!is_really_writable($this->upload_path)) {
            $this->set_error('upload_not_writable');
            return FALSE;
        }
        
        $this->upload_path = preg_replace("/(.+?)\/*$/", "\\1/", $this->upload_path);
        return TRUE;
    }
    
    
    //The main function which upload to s3
    public function upload_to_s3()
    {
        $ci =& get_instance();
        // Make sure it has a trailing slash but if the only thing is trailing slash then remove that so file can upload to root of Bucket
        if($this->upload_path == '/')
        {
                $this->upload_path = "";
        }
        
        $try   = 1;
        $sleep = 1;
        //Try multiple times to upload the file if not upload in one go by any reason.
        do {
            $result = $ci->aws->s3Client->putObject(array(
                                    'Bucket'     => $ci->aws->bucket_name,
                                    'Key'        => $this->upload_path . $this->file_name,
                                    'SourceFile' => $this->file_temp,
                                    'ACL'        => $this->acl,
                                    'ContentType' => $this->file_type
                                ));
            if ($result['ObjectURL']) {
                return true;
            }
            sleep($sleep);
            $sleep *= 2;
            
        } while (++$try < 6);
        
        $this->set_error('upload_destination_error');
        return false;
    }
    
    
    
    public function upload_to_s3_manually()
    {
        $CI =& get_instance();
        
        if ($this->s3_path == "" || $this->s3_path == "/")
        {
            $this->s3_path = $this->source_path;
        }
        
        $try   = 1;
        $sleep = 1;
        do {
            $result = $CI->cis3integration_lib->s3Client->putObject(array(
                                    'Bucket'     => $CI->cis3integration_lib->bucket_name,
                                    'Key'        => $this->s3_path . $this->file_name,
                                    'SourceFile' => $this->source_path . $this->original_file_name,
                                    'ACL'        => $this->acl,
                                    'ContentType' => $this->file_type
                                ));
            if ($result['ObjectURL']) {
                return true;
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 6);
        
        $this->set_error('upload_destination_error');
        return false;
    }
    
    public function do_upload_s3($field = 'userfile')
    {
        $ci =& get_instance();
        if (!isset($_FILES[$field])) {
            $this->set_error('upload_no_file_selected');
            return FALSE;
        }
        
        // Was the file able to be uploaded? If not, determine the reason why.
        if (!is_uploaded_file($_FILES[$field]['tmp_name'])) {
            $error = (!isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];
            
            switch ($error) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $this->set_error('upload_file_exceeds_limit');
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $this->set_error('upload_file_exceeds_form_limit');
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $this->set_error('upload_file_partial');
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $this->set_error('upload_no_file_selected');
                    break;
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $this->set_error('upload_no_temp_directory');
                    break;
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $this->set_error('upload_unable_to_write_file');
                    break;
                case 8: // UPLOAD_ERR_EXTENSION
                    $this->set_error('upload_stopped_by_extension');
                    break;
                default:
                    $this->set_error('upload_no_file_selected');
                    break;
            }
            
            return FALSE;
        }
        
        
        // Set the uploaded data as class variables
        $this->file_temp   = $_FILES[$field]['tmp_name'];
        $this->file_size   = $_FILES[$field]['size'];
        //Get mime type
        $file_mime_type = get_mime_by_extension($_FILES[$field]['name']);
        if($file_mime_type)
        {
            $this->file_type = $file_mime_type;
        }
        $this->file_name   = $this->_prep_filename($_FILES[$field]['name']);
        $this->file_ext    = $this->get_extension($this->file_name);
        $this->client_name = $this->file_name;
        
        // Is the file type allowed to be uploaded?
        if (!$this->is_allowed_filetype()) {
            $this->set_error('upload_invalid_filetype');
            return FALSE;
        }
        
        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->_file_name_override != '') {
            $this->file_name = $this->_prep_filename($this->_file_name_override);
            
            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === FALSE) {
                $this->file_name .= $this->file_ext;
            }
            
            // An extension was provided, lets have it!
            else {
                $this->file_ext = $this->get_extension($this->_file_name_override);
            }
            
            if (!$this->is_allowed_filetype(TRUE)) {
                $this->set_error('upload_invalid_filetype');
                return FALSE;
            }
        }
        
        // Convert the file size to kilobytes
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size / 1024, 2);
        }
        
        // Is the file size within the allowed maximum?
        if (!$this->is_allowed_filesize()) {
            $this->set_error('upload_invalid_filesize');
            return FALSE;
        }
        
        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basdir restriction.
        if (!$this->is_allowed_dimensions()) {
            $this->set_error('upload_invalid_dimensions');
            return FALSE;
        }
        
        // Sanitize the file name for security
        $this->file_name = sanitize_filename($this->file_name);
        
        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }
        
        // Remove white spaces in the name
        if ($this->remove_spaces == TRUE) {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }
        
        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;
        //echo $this->orig_name; exit;
        $ext = $this->getExtension($this->file_name);
        
	
        //create logic for new name`
        if($this->make_unique_filename){
            $this->file_name = $this->get_new_name($this->file_name);
        }
        

        //keep this
        if ($this->file_name === FALSE) {
                return FALSE;
        }
	
        
        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file.  Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean) {
            if ($this->do_xss_clean() === FALSE) {
                $this->set_error('upload_unable_to_write_file');
                return FALSE;
            }
        }
        
        //s3 code
        $this->upload_to_s3();

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        
        $this->set_image_properties($this->upload_path . $this->file_name);
        
        return TRUE;
    }
    
    //manually upload a file 
    public function do_upload_manually($source_path = '', $file_name = '', $s3_path = '') //if S3 path is not given then upload file to same path as source mean $file_path
    {
        $CI =& get_instance();
        //$this->file_name = $this->_prep_filename($file_name);
        $this->original_file_name = $this->file_name = $file_name;
        
        $this->file_ext = $this->get_extension($this->file_name);
        
        $this->source_path = $source_path;
        
        $this->s3_path = $s3_path;
        
        $this->file_resource = fopen($this->source_path . $this->file_name, 'r');
		
        //Get mime type
        $file_mime_type = get_mime_by_extension($this->source_path . $this->file_name);
        if($file_mime_type)
        {
            $this->file_type = $file_mime_type;
        }		
        
        //create logic for new name
        if($this->make_unique_filename){
            $this->file_name = $this->get_new_name($this->file_name);
        }
		
        if ($this->file_name === FALSE) {
            $this->set_error('upload_destination_error'); //just to set some error not cofnirm this is correct error
            return FALSE;
        }
        
        return $this->upload_to_s3_manually();
    }
    
    
    public function get_new_name($filename = '') //the received name is with extension so we have to return with extension
    {
        $filename     = str_replace($this->file_ext, '', $filename);
        $new_filename = '';
        $new_filename = $filename . time() . rand(1, 9999) . $this->file_ext;
        if ($new_filename == '') {
            $this->set_error('upload_bad_filename');
            return FALSE;
        } else {
            return $new_filename;
        }
    }
    
    public function getExtension($name)
    {
        $i = strrpos($name, ".");
        if (!$i) {
            return "";
        }
        $l   = strlen($name) - $i;
        $ext = substr($name, $i + 1, $l);
        return $ext;
    }
	
	
	
}