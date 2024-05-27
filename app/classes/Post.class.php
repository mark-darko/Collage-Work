<?php
    class Post extends Data {
        private $user;

        public function __construct(User $user)
        {
            $this->user = $user;
        }

        public function validate()
        {

        }

        public function load()
        {

        }

        public function save()
        {
            
        }
    }