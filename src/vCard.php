<?php

namespace App;

class vCard
{
    /** @var Cleaner */
    private $cleaner;

    /** @var string */
    private $fullName;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

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

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        if ($this->lastName !== null) {
            if ($this->firstName === null) {
                list(, $last) = explode(' ', $this->lastName);
                return trim($last, ' /(-');
            }
        }

        // No match, guessing from full names
        if ($this->fullName !== null) {
            if (strpos($this->fullName, ' ') !== false) {
                list(, $last) = explode(' ', $this->fullName);
                if ($last !== '') {
                    return trim($last, ' /(-');
                }
            }

            return $this->fullName;
        }

        // Still no match, trying from firstName
        if ($this->firstName !== null) {
            return 'prout';
            list(, $last) = explode(' ', $this->firstName);
            if ($last !== null) {
                return trim($last, ' /(-');
            }
        }

        return null;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);

        return $this;
    }
}
