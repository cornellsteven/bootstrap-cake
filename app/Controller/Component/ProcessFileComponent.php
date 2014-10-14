<?php
    
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');
    App::uses('String', 'Utility');
    
    /**
     * File upload processer
     * 
     *     Options:
     *        "whitelist" => array of valid file extensions (optional)
     *         "path"         => Path to directory where files should be saved (optional)
     *     
     *     On Success:
     *     Uploaded files are assigned to $this->files, with the following information
     *         [name] => New filename
     *         [type] => File type
     *         [size] => Size
     *         [ext]  => File's extension
     *         [path] => Absolute path to uploaded file
     *
     * On Failure:
     *     An error message will be assigned to $this->error
     *     If a specific file has an error, it will be assigned to $this->errors
     * 
     * @package App.Controller.Component
     * @version 1.0.0
     * @since     2.1.12
     * @author     Cornell Campbell
     */
    class ProcessFileComponent extends Component {
        
        public $components = array('Sanitizer');
        
        /**
         * Allowed file types
         *
         * @var string
         */
        public $whitelist = array(
            'png', 'gif', 'jpg', 'jpeg', 'zip', 
            'doc', 'xls', 'mpp', 'pdf', 'ppt', 
            'tiff', 'bmp', 'docx', 'xlsx', 'pptx', 
            'ps', 'odt', 'ods', 'odp', 'odg'
        );
        
        /**
         * File types that are NEVER allowed
         *
         * @var string
         */
        public $blacklist = array(
            # HTML may contain cookie-stealing JavaScript and web bugs
            'html', 'htm', 'js', 'jsb', 'mhtml', 'mht', 'xhtml', 'xht',
            # PHP scripts may execute arbitrary code on the server
            'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
            # Other types that may be interpreted by some servers
            'shtml', 'jhtml', 'pl', 'py', 'cgi',
            # May contain harmful executables for Windows victims
            'exe', 'scr', 'dll', 'msi', 'vbs', 'bat', 'com', 'pif', 'cmd', 'vxd', 'cpl',
        );
        
        /**
         * Component error message
         *
         * @var string
         */
        public $error = NULL;
        
        /**
         * Array of files that have errors
         *
         * @var string
         */
        public $errors = array();
        
        /**
         * Array of files successfully uploaded
         *
         * @var string
         */
        public $files = array();
        
        /**
         * Main function; invokes validate() and attmpts to move uploaded files
         *
         * @param string $files An array of POSTed files
         * @param string $options 
         * @return boolean
         * @author Cornell Campbell
         */
        public function save($files, $options = array()) {
            $this->reset();
            
            $defaults = array(
                'whitelist'    => $this->whitelist,
                'path'         => CMS_UPLOADS . date('Y') . DS . date('m') . DS,
                'prepend'     => '',
                'append'     => '-' . time(),
            );
            $options = array_merge($defaults, $options);
            $options['path'] = rtrim($options['path'], '/') . DS;
            
            if (isset($files['tmp_name'])) {
                $files = array($files);
            }
            
            // If path is writable, and files validate, upload files
            if ($this->validate($files, $options)) {
                $files = $this->files;
                $this->files = array();
                for ($i=0; $i < count($files); $i++) { 
                    $files[$i]['ext'] = strtolower(pathinfo($files[$i]['name'], PATHINFO_EXTENSION));
                    
                    // Rename file
                    $files[$i]['filename'] = $options['prepend'] . String::truncate($this->Sanitizer->clean(pathinfo($files[$i]['name'], PATHINFO_FILENAME)), 30, array('ellipsis' => '')) . $options['append'] . '.' . $files[$i]['ext'];
                    
                    // Check for existing file
                    $counter = 1;
                    while (file_exists($options['path'] . DS . $files[$i]['filename'])) {
                        if ($counter > 500) {
                            break;
                        }
                        
                        $files[$i]['filename'] = $options['prepend'] . String::truncate($this->Sanitizer->clean(pathinfo($files[$i]['name'], PATHINFO_FILENAME)), 30, array('ellipsis' => '')) . $options['append'] . "-$counter." . $files[$i]['ext'];
                        
                        $counter++;
                    }
                    
                    // Set-up path
                    $filepath = $options['path'] . $files[$i]['filename'];
                    
                    // Move to permanent location
                    if (move_uploaded_file($files[$i]['tmp_name'], $filepath)) {
                        $this->files[] = array(
                            'name' => $files[$i]['filename'],
                            'type' => $files[$i]['type'],
                            'error' => 0,
                            'size' => $files[$i]['size'],
                            'ext' => $files[$i]['ext'],
                            'path' => $filepath,
                            'uploaded' => 1,
                        );
                    } else {
                        $files[$i]['error'] = 'File failed to write to disk';
                        $files[$i]['uploaded'] = 0;
                        $this->errors[] = $files[$i];
                    }
                }
            }
            
            // Check for errors
            if (count($this->errors) && $this->error === NULL) {
                $this->error = 'Upload failed due to errors with one or more file';
                return false;
            }
            if ($this->error !== NULL) {
                return false;
            }
            
            return true;
        }
        
        /**
         * Used to validate upload path and files
         *
         * @param string $files 
         * @param string $options 
         * @return boolean
         * @author Cornell Campbell
         */
        public function validate($files, $options) {
            
            // Check if directory exists
            if ( ! file_exists($options['path'])) {
                
                // Attempt to create the file
                $folder = new Folder($options['path'], true, 0777);
            }
            
            // Check directory permissions
            if ( ! is_writable($options['path'])) {
                $this->error = 'Directory not writable';
                return false;
            }
            
            // Validate files
            foreach ($files as $file) { 
                $file['ext'] = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                // Check for file errors
                if ($file['error']) {
                    $file['error'] = $this->fileError($file['error']);
                    $this->errors[] = $file;
                    continue;
                }
                
                // Start validation
                // Allow ALL file types (except those in $this->blacklist)
                if ($options['whitelist'] === '*') {
                    if (in_array($file['ext'], $this->blacklist)) {
                        $file['error'] = 'File type not allowed';
                        $this->errors[] = $file;
                        continue;
                    }
                } 
                
                // Allow ONLY file types in whitelist
                else {
                    if ( ! in_array($file['ext'], $options['whitelist'])) {
                        $file['error'] = 'File type not allowed';
                        $this->errors[] = $file;
                        continue;
                    }
                }
                
                $this->files[] = $file;
            }
            
            if (count($this->errors) || ! count($this->files)) {
                return false;
            } else {
                return true;
            }
            
        }
        
        /**
         * Resets the component to its original state
         *
         * @return void
         * @author Cornell Campbell
         */
        public function reset() {
            $this->files = array();
            $this->errors = array();
            $this->error = NULL;
        }
        
        /**
         * Returns a human-friendly message corresponding to PHP file error code
         *
         * @param string $err 
         * @return void
         * @author Cornell Campbell
         */
        private function fileError($err) {
            switch ($err) {
                case UPLOAD_ERR_OK:             return 'No errors'; break;
                case UPLOAD_ERR_INI_SIZE:         return 'File exceeds the max filesize'; break;
                case UPLOAD_ERR_FORM_SIZE:         return 'File exceeds the max filesize'; break;
                case UPLOAD_ERR_PARTIAL:         return 'File was only partially uploaded'; break;
                case UPLOAD_ERR_NO_FILE:         return 'No file was uploaded'; break;
                case UPLOAD_ERR_NO_TMP_DIR:     return 'Temporary folder is missing'; break;
                case UPLOAD_ERR_CANT_WRITE:     return 'File failed to write to disk'; break;
                case UPLOAD_ERR_EXTENSION:         return 'A PHP extension stopped the file upload'; break;
                default:                         return 'File could not uploaded due to an unknown error'; break;
            }
            
            return 'Unknown error';
        }
        
    }
    
?>