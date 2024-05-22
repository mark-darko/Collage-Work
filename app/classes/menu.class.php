<?php
    class Menu {
        private $menu;
        private $active_link;

        /**
         * @param array $menu
         */
        public function __construct(array $menu, string $active_link)
        {
            $this->menu = $menu;
            $this->active_link = $active_link;
        }

        public function get_menu ()
        {
            $top_side = '<aside id="colorlib-aside" role="complementary" class="js-fullheight">
                            <nav id="colorlib-main-menu" role="navigation">
                                <ul>';
            $bottom_side = '</ul>
                        </nav>
                    </aside>';

            $links = '';

            foreach ($this->menu as $title => $page) {
                if ($this->active_link == $page)
                    $links .= '<li class="colorlib-active"><a href="' . $page .'">' . $title . '</a></li>';
                else
                    $links .= '<li><a href="' . $page .'">' . $title . '</a></li>';
            }

            return $top_side . $links . $bottom_side;
        }
    }