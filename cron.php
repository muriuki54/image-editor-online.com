<?php

class CronManager {
    private $connection;
    private $path;
    private $handle;
    private $cronFile;

    /**
     * @method __construct: Responsible for establishing and authenticating the SSH2 connection
     * @params 
     *  $host     : the IP Adrress of the remote server
     *  $port     : port used for the SSH2 connection
     *  $username : users log in name for the server
     *  $password : users password for the server
     */
    public function __construct($host=NULL, $port=NULL, $username=NULL, $password=NULL) {
        # Define property $this->path representing the default directory for temporary cron files
        $this->path = dirname(__FILE__);
        $this->handle = "crontab.txt";
        $this->cronFile = "{$this->path}".DIRECTORY_SEPARATOR."{$this->handle}";

        # Try to establish connection to the server
        try {

            if(is_null($host) || is_null($port) || is_null($username) || is_null($password)) { // Missing credentials
                throw new CronException("One or more server connection credentials missing");
            }

            $this->connection = @ssh_connect($host, $port);
            if(! $this->connection) throw new CronException("SSH2 connection could not be established");

            $authentication = @ssh2_auth_password($this->connection, $username, $password);
            if(! $authentication) throw new CronException("Authentication failed for '{$username}' using password: {$password}");
        } catch(CronException $e) {
            // $this->CronError($e->getMessage());
            $this->CronError(); // die
        }
    }

    /**
     * @method execute: Responsible for executing commands on the remote server
     * @params: At least 1 expected
     *         : The params(args) represent the commands we want to run using the ssh2_exec() function
     */
    public function execute() {
        $argumentCount = func_num_args(); // number of passed args
        try {
            if(! $argumentCount) throw new CronException("No commands to execute: 0 args specified");

            # Create a Linux command string based on the passed args
            $arguments = func_get_args();
            $commandString = ($argumentCount > 1) ? implode(" && ", $arguments) : $arguments[0];

            # Execute the command string using ssh2_exec()
            # Expects the connection currently stored in $this->connection as well as the command string

            # Streams are defined as a resource object which exhibits streamable behavior... which can be read from or written to in a linear fashion.
            $stream = @ssh_exec($this->connection, $commandString); // use @ to supress error as we are throwing out own exception
            if(! $stream) throw new CronException("Unable to execute the specfied commands: {$commandString}");
        } catch(CronException $e) {
            $this->CronError();
        }

        return $this; // To maintain method chainabilty
    }

    /**
     * @method writeToFile: Responsible for writing the existing cronTab to a temp file or creating a blank temp. file
     * @params:
     *  $path   :Temp-file path
     *  $handle : Name we want to use when creating it
     */
    public function writeToFile($path=NULL, $handle=NULL) {
        # Check if cron file already exists using a user defined method -> cronFileExists()
        if ( ! cronFileExists()) {
            $this->handle = (is_null($handle)) ? $this->handle : $handle;
            $this->path = (is_null($path)) ? $this->path : $path;

            $this->cronFile = "{$this->path}".DIRECTORY_SEPARATOR."{$this->handle}";

            # Set  our cronFile as the standard output
            # " > " in linux will create the file if it does exist
            # we can then use the || or "or" operator after this conditional to create a new blank file if needed
            $init_cron = "crontab -l > {$this->cronFile} && [ -f {$this->cronFile} ] || > {$this->cronFile}";

            $this->execute($init_cron);
        }

        return $this;
    }

    /**
     * @method removeFile: Responsible for removing the temp cron file
     */
    public function removeFile() {
        if($this->cronFileExists()) $this->execute("rm {$this->cronFile}");
        return $this;
    }

    /**
     * @method appendCronjob : Responsible for adding new jobs / lines to the temp cron file and the executing the crontab command
     * @params: 
     *  $cronJobs: A string or array of strings representing the cron jobs to append
     */
    public function appendCronjob($cronJobs=NULL) {
        if(is_null($cronJobs)) {
            try {
                throw new CronException("No cron (s) specified");
            } catch(CronException $e) {
                $this->CronError();
            }
        }
    }

    /**
     * @method cronFileExists: Utility function to check if cron file already exists
     */
    public function cronFileExists() {
        return file_exists($this->cronFile);
    }

    /**
     * @method CronError: Responsible for ending execution of this here script incase of an error
     */
    public function CronError() {
        die();
    }
}

class CronException extends Exception {
    public function __construct($error) {
        $this->errorMessage = $error;
        $this->logErrorToFile();
    }

    private function logErrorToFile() {
        file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR. "logs" . DIRECTORY_SEPARATOR . "cronlogs.txt", date("d-m-Y") . " " . date("h:s:ia") . "{$this->errorMessage} \n", FILE_APPEND);
    }
}