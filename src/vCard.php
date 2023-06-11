<?php

namespace App;

class vCard
{
    /** @var Cleaner */
    private $cleaner;

    /** @var string */
    private $fullName;

    /** @var string[] */
    private $emails = [];

    /** @var string[] */
    private $tels = [];

    public function __construct()
    {
        $this->cleaner = new Cleaner();
    }

    public function isEmpty(): bool
    {
        return $this->fullName === null;
    }


    public function getFullName()
    {
        return $this->fullName;
    }


    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * Get the value of emails
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Set the value of emails
     *
     * @return  self
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * Get the value of tels
     */
    public function getTels()
    {
        return $this->tels;
    }

    /**
     * Set the value of tels
     *
     * @return  self
     */
    public function setTels($tels)
    {
        foreach ($tels as $key => $value) {
            $this->tels[$key] = $this->cleaner->cleanPhone($value);
        }

        return $this;
    }
}
