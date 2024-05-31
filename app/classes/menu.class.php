<?php
    class Menu {
        private $menu;
        private $active_link;
        private $response;
        private $user;

        /**
         * @param array $menu
         */
        public function __construct(array $menu, string $active_link, Response $response, User $user)
        {
            $this->menu = $menu;
            $this->active_link = $active_link;
            $this->response = $response;
            $this->user = $user;
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
                if (($item["link"] == '/app/login.php' || $item["link"] == '/app/register.php') && $this->user->id) continue;
                if ($item["link"] == '/app/logout.php' && !$this->user->id) continue;

                if ($this->active_link == $item["link"])
                    $links .= '<li class="colorlib-active"><a href="' . $this->response->getLink($item["link"]) .'">' . $item["title"] . '</a></li>';
                else
                    $links .= '<li><a href="' . $this->response->getLink($item["link"]) .'">' . $item["title"] . '</a></li>';
            }

            return $top_side . $links . $bottom_side;
        }
    }