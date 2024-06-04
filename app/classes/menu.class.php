<?php
    class Menu {
        private array $menu;
        public string $active_link;
        private Response $response;
        private User $user;

        /**
         * Конструктор класса
         * @param array $menu
         * @param string $active_link
         * @param Response $response
         * @param User $user
         */
        public function __construct($menu, $response, $user)
        {
            $this->menu = $menu;
            $this->active_link = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            $this->response = $response;
            $this->user = $user;
        }

        /**
         * Возвращает html меню
         * @return string
         */
        public function get_menu ()
        {
            $top_side = '<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
                        <aside id="colorlib-aside" role="complementary" class="js-fullheight">
                            <nav id="colorlib-main-menu" role="navigation">
                                <ul>';
            $bottom_side = '</ul>
                        </nav>
                    </aside>';

            $links = '';

            foreach ($this->menu as $item) {
                if (($item["link"] == '/app/login.php' || $item["link"] == '/app/register.php') && $this->user->id) continue;
                if ($item["link"] == '/app/logout.php' && !$this->user->id) continue;
                if ($item["link"] == '/app/users.php' && $this->user->isAdmin && $this->user->isBlocked) continue;

                if ($this->active_link == $item["link"])
                    $links .= '<li class="colorlib-active"><a href="' . $this->response->getLink($item["link"]) .'">' . $item["title"] . '</a></li>';
                else
                    $links .= '<li><a href="' . $this->response->getLink($item["link"]) .'">' . $item["title"] . '</a></li>';
            }

            return $top_side . $links . $bottom_side;
        }
    }