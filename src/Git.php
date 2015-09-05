<?php 
namespace HazeDevelopment;

Class Git
{
    public $repo;
    public $location;
    public $user;
    public $password;
    public $ssh;

    public function __construct($repo, $location, $username = false, $password = false, $ssh = true)
    {
        $this->repo = $repo;
        $this->location = $location;
        $this->user = $username;
        $this->password = $password;
        $this->ssh = $ssh;
    }

    public function command($command)
    {
        $output = [];
        exec($command.' 2>&1', $output);

        return $output;
    }

    public function cloneRepo()
    {
        $cloneUrl = $this->repo;
        if(!$this->ssh)
        {
            $cloneUrl = 'http://'.$this->user;
            if($this->password)
            {
                $cloneUrl .= ':'.$this->password;
            }

            $cloneUrl .= '@'.str_replace(['http://', 'https://'], '', $this->repo);
        }

        return $this->command('git clone '.$cloneUrl.' "'.$this->location.'"');
    }

    public function branch($branch)
    {
        return $this->command('cd '.$this->location.' && git branch '.$branch);
    }

    public function checkout($branch)
    {
        return $this->command('cd '.$this->location.' && git checkout '.$branch);
    }

    public function add($files)
    {
        if(is_array($files))
        {
            $files = implode(' ', $files);
        }

        return $this->command('cd '.$this->location.' && git add '.$files);
    }

    public function commit($message)
    {
        return $this->command('cd '.$this->location.' && git commit -m '.$message);
    }

    public function pull($origin = 'origin', $branch = 'master')
    {
        return $this->command('cd '.$this->location.' && git pull '.$origin.' '.$branch);
    }

    public function push($origin = 'origin', $branch = 'master')
    {
        return $this->command('cd '.$this->location.' && git push '.$origin.' '.$branch);
    }
}