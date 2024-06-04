<?php
    class Middleware {
        /**
         * Проверка разрешений доступа
         * @param User $user
         * @param Response $response
         * @param array $permissions
         */
        public static function checkPermissions($user, $response, $permissions)
        {
            foreach($permissions as $permission => $pages) {
                foreach($pages as $page) {
                    if ($page == parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) && $user->$permission)
                        $response->redirect('/app/index.php');
                }
            }
        }
    }