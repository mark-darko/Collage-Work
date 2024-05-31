<?php
    class Menu {
        private $menu;
        private $active_link;
        private $response;

        /**
         * @param array $menu
         */
        public function __construct(array $menu, string $active_link, Response $response)
        {
            $this->menu = $menu;
            $this->active_link = $active_link;
            $this->response = $response;
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

            foreach ($this->menu as $item) {
                if ($this->active_link == $item["link"])
                    $links .= '<li class="colorlib-active"><a href="' . $this->response->getLink($item["link"]) .'">' . $$item["title"] . '</a></li>';
                else
                    $links .= '<li><a href="' . $this->response->getLink($item["link"]) .'">' . $item["title"] . '</a></li>';
            }

            return $top_side . $links . $bottom_side;
        }
    }