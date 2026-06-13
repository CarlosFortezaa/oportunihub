<?php

class Opportunity{
    private $opp_id, $title, $description, $sponsor, $url, $attachment_path, $deadline, $date_posted, $posted_by, $type;

    public function __construct($title, $description, $sponsor, $type, $opp_id = null,  $url = null, $attachment_path = null, $deadline = null, $date_posted = null, $posted_by = null){
        $this->title = $title;
        $this->description = $description;
        $this->sponsor = $sponsor;
        $this->type = $type;
        $this->opp_id = $opp_id;
        $this->url = $url;
        $this->attachment_path = $attachment_path;
        $this->deadline = $deadline;
        $this->date_posted = $date_posted;
        $this->posted_by = $posted_by;
        
    }

    // Getters
    public function getOppId() {
        return $this->opp_id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getSponsor(){
        return $this->sponsor;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getAttachmentPath(){
        return $this->attachment_path;
    }

    public function getDeadline(){
        return $this->deadline;
    }

    public function getDatePosted() {
        return $this->date_posted;
    }

    public function getPostedBy() {
        return $this->posted_by;
    }

    public function getType() {
        return $this->type;
    }

    // Setters
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setSponsor($sponsor) {
        $this->sponsor = $sponsor;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setAttachmentPath($attachment_path) {
        $this->attachment_path = $attachment_path;
    }

    public function setDeadline($deadline) {
        $this->deadline = $deadline;
    }

    public function setType($type) {
        $this->type = $type;
    }

    // Verifica si una oportunidad esta vencida para reflejar badge de "VENCIDA" en opportunities_List.php
    public function isExpired() {
        // Si no hay fecha limite la oportunidad no ha vencido
        if (empty($this->deadline)) {
            return false;
        }
        // Comparando la fecha limite con el tiempo actual
        return strtotime($this->deadline) < time();
    }
}
