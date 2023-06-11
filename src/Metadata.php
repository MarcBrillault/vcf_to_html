<?php

namespace App;

class Metadata
{
    private  $charset;
    private  $encoding;
    private $pref = false;
    private $home = false;
    private $cellPhone = false;

    /**
     * Get the value of charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set the value of charset
     *
     * @return  self
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Get the value of encoding
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set the value of encoding
     *
     * @return  self
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Get the value of pref
     */
    public function getPref()
    {
        return $this->pref;
    }

    /**
     * Set the value of pref
     *
     * @return  self
     */
    public function setPref($pref)
    {
        $this->pref = $pref;

        return $this;
    }

    /**
     * Get the value of home
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set the value of home
     *
     * @return  self
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get the value of cellPhone
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set the value of cellPhone
     *
     * @return  self
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }
}
